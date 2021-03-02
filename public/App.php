<?php

class App {
    protected $controller = "home";
    protected $action = "index";
    protected $params = [];

    function __construct() {
        $url_params = $this->parseUrl();

        if (isset($url_params[0])) {
            if (file_exists('./app/Controllers/'.$url_params[0].'Controller.php')) {
                $this->controller = $url_params[0];
                unset($url_params[0]);
            }
        }

        require_once './app/Controllers/' . $this->controller . 'Controller.php';
        $controller = $this->controller.'Controller';
        $this->controller = new $controller;


        if (isset($url_params[1])) {
            if (method_exists($this->controller, $url_params[1])) {
                $this->action = $url_params[1];
                unset($url_params[0]);
            }
        }

        $this->params = $url_params ? array_values($url_params) : [];

        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    function parseUrl() {
        if (isset($_SERVER['REQUEST_URI'])) {
            return $url =  explode('/', filter_var(trim($_SERVER['REQUEST_URI'],'/'), FILTER_SANITIZE_URL));
        }
    }
}
