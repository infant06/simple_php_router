<?php

class Router
{
    private $routes = [];
    private $notFoundHandler;

    /**
     * Add a GET route
     */
    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Add a POST route
     */
    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Add a PUT route
     */
    public function put($path, $handler)
    {
        $this->addRoute('PUT', $path, $handler);
    }

    /**
     * Add a DELETE route
     */
    public function delete($path, $handler)
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    /**
     * Add a route for any HTTP method
     */
    public function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'pattern' => $this->convertToPattern($path)
        ];
    }

    /**
     * Set a handler for 404 not found errors
     */
    public function setNotFoundHandler($handler)
    {
        $this->notFoundHandler = $handler;
    }    /**
         * Run the router and dispatch the request
         */
    public function run()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod) {
                $matches = [];
                if (preg_match($route['pattern'], $requestUri, $matches)) {
                    // Remove the full match from the beginning
                    array_shift($matches);

                    // Extract named parameters from URL path
                    $params = $this->extractNamedParams($route['path'], $requestUri);

                    // Merge with query parameters from $_GET
                    $allParams = array_merge($matches, $params, $_GET);

                    return $this->callHandler($route['handler'], $allParams);
                }
            }
        }

        // No route found, call 404 handler
        return $this->callNotFoundHandler();
    }

    /**
     * Convert route path to regex pattern
     */
    private function convertToPattern($path)
    {
        // Convert {param} to named capture groups
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $path);
        // Convert :param to numbered capture groups for backward compatibility
        $pattern = preg_replace('/:([a-zA-Z_][a-zA-Z0-9_]*)/', '([^/]+)', $pattern);

        return '#^' . $pattern . '$#';
    }

    /**
     * Extract named parameters from the URL
     */
    private function extractNamedParams($routePath, $requestUri)
    {
        $params = [];
        $routeParts = explode('/', trim($routePath, '/'));
        $uriParts = explode('/', trim($requestUri, '/'));

        for ($i = 0; $i < count($routeParts); $i++) {
            if (isset($uriParts[$i]) && preg_match('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $routeParts[$i], $matches)) {
                $params[$matches[1]] = $uriParts[$i];
            }
        }

        return $params;
    }    /**
         * Call the route handler
         */
    private function callHandler($handler, $params = [])
    {
        // Convert named parameters to positional parameters for function calls
        // But preserve access to all parameters (including query params)
        $positionalParams = [];
        $numericKeys = array_filter(array_keys($params), 'is_numeric');

        // Add positional parameters first (from URL path)
        foreach ($numericKeys as $key) {
            $positionalParams[] = $params[$key];
        }

        if (is_callable($handler)) {
            // For closures, we can pass an additional parameter with all params
            if ($handler instanceof Closure) {
                $reflection = new ReflectionFunction($handler);
                $paramCount = $reflection->getNumberOfParameters();

                // If handler expects more parameters than we have positional ones,
                // pass the full params array as the last parameter
                if ($paramCount > count($positionalParams)) {
                    $positionalParams[] = $params;
                }
            }

            return call_user_func_array($handler, $positionalParams);
        } elseif (is_string($handler)) {
            // Handle "Controller@method" format
            if (strpos($handler, '@') !== false) {
                list($controller, $method) = explode('@', $handler);
                if (class_exists($controller)) {
                    $instance = new $controller();
                    if (method_exists($instance, $method)) {
                        // Set query parameters as a property for controller access
                        $instance->queryParams = $params;
                        return call_user_func_array([$instance, $method], $positionalParams);
                    }
                }
            }
        }

        throw new Exception("Handler not found or not callable");
    }

    /**
     * Call the 404 not found handler
     */
    private function callNotFoundHandler()
    {
        if ($this->notFoundHandler && is_callable($this->notFoundHandler)) {
            return call_user_func($this->notFoundHandler);
        }

        // Default 404 response
        http_response_code(404);
        echo "404 - Page Not Found";
    }    /**
         * Get all registered routes
         */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Get query parameter value
     */
    public static function getQuery($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }

        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
}
