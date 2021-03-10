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
            header('Location:/post/index');
        } else {
            $message = [
                'type' => 'error',
                'status' => '406',
                'message' => 'Login fail!'
            ];
            return $this->view('auth/login', $message);
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
            $message = [
                'type' => 'error',
                'status' => '400',
                'message' => 'User existed! Create failed!'
            ];
            return $this->view('auth/register', $message);

        }
        if ($user->create($params)) {
            $message = [
                'type' => 'success',
                'status' => '200',
                'message' => 'Account is created!'
            ];

            return $this->view('auth/register', $message);
        } else {
            $message = [
                'type' => 'error',
                'status' => '400',
                'message' => 'Cannot create account!'
            ];
            return $this->view('auth/register', $message);
        }
    }
}
