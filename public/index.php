<?php
require_once "./App.php";
require_once "Route.php";

session_start();
define('AUTH_REQUIRED', true);
define('AUTH_NOT_REQUIRED', false);

$request_url = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$request_method = !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

$router = new Route();
require_once "routes.php";

$router->handle($request_url, $request_method);
