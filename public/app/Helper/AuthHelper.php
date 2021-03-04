<?php
require_once 'Helper.php';

class AuthHelper extends Helper
{
    public static function checkAuth(): bool
    {
        return isset($_SESSION['user']);
    }
}