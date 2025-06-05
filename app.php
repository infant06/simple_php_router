<?php

class AppController
{
    public $queryParams = [];

    public function index()
    {
        return $this->view('home', ['title' => 'Welcome to PHP Router']);
    }

    public function about()
    {
        return $this->view('about', ['title' => 'About Us']);
    }

    public function contact()
    {
        return $this->view('contact', ['title' => 'Contact Us']);
    }

    public function user($id, $name = null)
    {
        $data = [
            'title' => 'User Profile',
            'user_id' => $id,
            'user_name' => $name,
            'query_params' => $this->queryParams
        ];

        return $this->view('user', $data);
    }

    /**
     * Get query parameter value
     */
    protected function getQuery($key = null, $default = null)
    {
        if ($key === null) {
            return $this->queryParams;
        }

        return isset($this->queryParams[$key]) ? $this->queryParams[$key] : $default;
    }
    private function view($template, $data = [])
    {
        extract($data);

        ob_start();
        include "views/{$template}.php";
        $content = ob_get_clean();

        echo $content;
        return $content;
    }
}
