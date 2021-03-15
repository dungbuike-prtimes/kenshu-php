<?php
require_once "./App.php";
require_once "Route.php";

session_start();
define('AUTH_REQUIRED', true);
define('AUTH_NOT_REQUIRED', false);

$request_url = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$request_method = !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
if ($request_method == 'POST') {
    if (isset($_POST['__method'])) {
        switch ($_POST['__method']) {
            case 'PUT': {
                $request_method = 'PUT';
                break;
            }
            case 'PATCH': {
                $request_method = 'PATCH';
                break;
            }
            case 'DELETE': {
                $request_method = 'DELETE';
                break;
            }
        }
    }
}

/**
 * HTML特殊文字をエスケープする関数
 *
 * @param $str 対象の文字列
 * @return 処理された文字列
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


$router = new Route();
require_once "routes.php";

$router->handle($request_url, $request_method);
