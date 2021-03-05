<?php
require_once 'BaseController.php';
require_once(__DIR__ . '/../Models/User.php');

class userController extends BaseController
{
    function __construct()
    {

    }

    public function login()
    {
        return $this->view('auth/login');
    }

    public function auth()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = new User();
        $result = $user->auth($email, $password);

        if ($result) {
            echo "Loged in";
            $_SESSION['user']['username'] = $result['username'];
            $_SESSION['user']['email'] = $result['email'];
            $_SESSION['user']['id'] = $result['id'];
        } else {
            $message = [
                'type' => 'error',
                'status' => '406',
                'message' => 'Login fail!'
            ];
            $this->view('auth/login', $message);
        }
    }

    public function logout() {
        unset($_SESSION['user']);
        echo "loged out!";
    }

    public function register()
    {
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
            echo "User existed! Create fail.";
            return;
        }
        if ($user->create($params)) {
            echo "create success!";
        } else {
            echo "create fail!";
        }
    }
}
