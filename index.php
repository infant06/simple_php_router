<?php

// Include the router and controllers
require_once 'Router.php';
require_once 'app.php';

// Create a new router instance
$router = new Router();

// Define routes using closures/anonymous functions
$router->get('/', function () {
    echo "<h1>Welcome to PHP Router!</h1>";
    echo "<p>This is the home page.</p>";

    echo "<h3>Basic Routes:</h3>";
    echo "<ul>";
    echo "<li><a href='/about'>About</a></li>";
    echo "<li><a href='/contact'>Contact</a></li>";
    echo "<li><a href='/user/123'>User Profile (ID: 123)</a></li>";
    echo "<li><a href='/user/456/john'>User Profile (ID: 456, Name: john)</a></li>";
    echo "</ul>";

    echo "<h3>Query Parameter Examples:</h3>";
    echo "<ul>";
    echo "<li><a href='/search?q=php&category=tutorial'>Search with query params</a></li>";
    echo "<li><a href='/profile?id=789&theme=dark'>Profile with query params</a></li>";
    echo "</ul>";

    echo "<h3>API Endpoints:</h3>";
    echo "<ul>";
    echo "<li><a href='/api/users'>API Users</a></li>";
    echo "</ul>";
});

$router->get('/about', function () {
    echo "<h1>About Us</h1>";
    echo "<p>This is the about page.</p>";
    echo "<a href='/'>← Back to Home</a>";
});

$router->get('/contact', function () {
    echo "<h1>Contact Us</h1>";
    echo "<p>This is the contact page.</p>";
    echo "<a href='/'>← Back to Home</a>";
});

// Routes with parameters using {param} syntax
$router->get('/user/{id}', function ($id) {
    echo "<h1>User Profile</h1>";
    echo "<p>User ID: " . htmlspecialchars($id) . "</p>";
    echo "<a href='/'>← Back to Home</a>";
});

$router->get('/user/{id}/{name}', function ($id, $name) {
    echo "<h1>User Profile</h1>";
    echo "<p>User ID: " . htmlspecialchars($id) . "</p>";
    echo "<p>User Name: " . htmlspecialchars($name) . "</p>";
    echo "<a href='/'>← Back to Home</a>";
});

// Routes using controller@method syntax
$router->get('/home', 'AppController@index');
$router->get('/home/about', 'AppController@about');
$router->get('/home/contact', 'AppController@contact');

// API routes
$router->get('/api/users', function () {
    header('Content-Type: application/json');
    echo json_encode([
        'users' => [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com']
        ]
    ]);
});

// Example route that demonstrates query parameters
$router->get('/search', function ($allParams) {
    echo "<h1>Search Results</h1>";

    $query = isset($allParams['q']) ? $allParams['q'] : '';
    $category = isset($allParams['category']) ? $allParams['category'] : 'all';
    $page = isset($allParams['page']) ? $allParams['page'] : 1;

    echo "<p>Search Query: " . htmlspecialchars($query) . "</p>";
    echo "<p>Category: " . htmlspecialchars($category) . "</p>";
    echo "<p>Page: " . htmlspecialchars($page) . "</p>";

    echo "<h3>Try these URLs:</h3>";
    echo "<ul>";
    echo "<li><a href='/search?q=php'>Search for 'php'</a></li>";
    echo "<li><a href='/search?q=router&category=code'>Search 'router' in 'code' category</a></li>";
    echo "<li><a href='/search?q=tutorial&category=docs&page=2'>Search 'tutorial' in 'docs', page 2</a></li>";
    echo "</ul>";

    echo "<a href='/'>← Back to Home</a>";
});

// Simple route that uses Router::getQuery() helper
$router->get('/profile', function () {
    echo "<h1>User Profile</h1>";

    $userId = Router::getQuery('id', 'guest');
    $theme = Router::getQuery('theme', 'light');

    echo "<p>User ID: " . htmlspecialchars($userId) . "</p>";
    echo "<p>Theme: " . htmlspecialchars($theme) . "</p>";

    echo "<h3>Try these URLs:</h3>";
    echo "<ul>";
    echo "<li><a href='/profile?id=123'>Profile with ID 123</a></li>";
    echo "<li><a href='/profile?id=456&theme=dark'>Profile with ID 456, dark theme</a></li>";
    echo "</ul>";

    echo "<a href='/'>← Back to Home</a>";
});

$router->post('/api/users', function () {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);

    // In a real application, you would save to database
    echo json_encode([
        'message' => 'User created successfully',
        'user' => $input
    ]);
});

// Set a custom 404 handler
$router->setNotFoundHandler(function () {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
    echo "<p>The page you are looking for does not exist.</p>";
    echo "<a href='/'>← Back to Home</a>";
});

// Run the router
try {
    $router->run();
} catch (Exception $e) {
    http_response_code(500);
    echo "<h1>500 - Internal Server Error</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
