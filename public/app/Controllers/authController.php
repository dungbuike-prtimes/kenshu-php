<?php
require_once 'BaseController.php';

class authController extends BaseController
{
    function __construct()
    {

    }

    public function login() {
        return $this->view('auth/login');
    }

    public function postLogin() {
        print_r($_POST);
    }
}
