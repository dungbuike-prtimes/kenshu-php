<?php
require_once 'Helper.php';

class AuthHelper extends Helper
{
    public static function checkAuth(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function getUserId() {
        return isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
    }
}