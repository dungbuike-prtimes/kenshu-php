<?php
require_once 'BaseController.php';
include_once __DIR__.'/../Helper/AuthHelper.php';
require_once(__DIR__ . '/../Models/User.php');

class userController extends BaseController
{
    function __construct()
    {

    }

    public function login()
    {
        if (AuthHelper::checkAuth()) {
            header("location:/post/index");
        }
        return $this->view('auth/login');
    }

    public function auth()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = new User();
        $result = $user->auth($email, $password);

        if ($result) {
            $_SESSION['user']['username'] = $result['username'];
            $_SESSION['user']['email'] = $result['email'];
            $_SESSION['user']['id'] = $result['id'];
            header('Location:/posts');
        } else {
            return $this->flash('error','406','Login fail!')->view('auth/login');
        }
    }

    public function logout() {
        unset($_SESSION['user']);
        header('location:/auth/login');
    }

    public function register()
    {
        if (AuthHelper::checkAuth()) {
            header("location:/post/index");
        }
        return $this->view('auth/register');
    }

    public function create()
    {
        $params = [];
        $params['email'] = $_POST['email'];
        $params['username'] = $_POST['username'];
        $params['phone_number'] = $_POST['phone_number'];
        $params['password'] = $_POST['password'];
        $params['confirm_password'] = $_POST['confirm_password'];

        $user = new User;
        if ($user->isExisted($params['email'])) {
            return $this->flash('error','400','User existed! Create failed!')
                ->view('auth/register');

        }
        if ($user->create($params)) {
            return $this->flash('success','200','Account is created!')
                ->view('auth/register');
        } else {
            return $this->flash('error','400','Cannot create account!')
                ->view('auth/register');
        }
    }
}
