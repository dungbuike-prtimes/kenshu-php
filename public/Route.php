<?php
require_once __DIR__.'/app/Helper/AuthHelper.php';

class Route
{

    private $route_map;

    function __construct()
    {
        $this->route_map = [];

    }

    public function get(string $url, $action, $checkAuth = AUTH_NOT_REQUIRED)
    {
        $this->assignRequest($url, 'GET', $action, $checkAuth);
    }

    public function post(string $url, $action, $checkAuth = AUTH_NOT_REQUIRED)
    {
        $this->assignRequest($url, 'POST', $action, $checkAuth);
    }

    public function assignRequest(string $url, string $method, $action, $checkAuth = AUTH_NOT_REQUIRED)
    {
        if (preg_match_all('/({([a-zA-Z]+)})/', $url, $params)) {
            $url = preg_replace('/({([a-zA-Z]+)})/', '(.+)', $url);
        }
        $url = str_replace('/', '\/', $url);

        $route = [
            'url' => $url,
            'method' => $method,
            'action' => $action,
            'checkAuth' => $checkAuth,
            'params' => $params[2],
        ];

        array_push($this->route_map, $route);
    }

    public function handle(string $url, string $method)
    {
        foreach ($this->route_map as $route) {
            if ((!($route["checkAuth"]) || AuthHelper::checkAuth()) && ($route['method'] == $method)) {
                // only case $route["checkAuth"]=Auth_Require(true) and AuthHelper::checkAuth()=false that can't access
                $reg = '/^'. $route['url'] . '$/';
                if (preg_match($reg, $url, $params)) {
                    array_shift($params);
                    $this->__call_action_route($route['action'], $params);
                    return;
                }
            }
        }
        echo "404 - Not Found";
        return;
    }

    public function __call_action_route($action, $params) {
        if (is_callable($action)) {
            call_user_func_array($action, $params);
            return;
        }

        if (is_string($action)) {
            $action = explode('@', $action);
            $controller_name = __DIR__.'/app/Controllers/' . $action[0];
            require_once $controller_name.'.php';
            $controller = new $action[0]();
            call_user_func_array([$controller, $action[1]], $params);
            return;
        }
    }
}
