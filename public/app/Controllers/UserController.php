<?php
require_once 'BaseController.php';
include_once __DIR__.'/../Helper/AuthHelper.php';
require_once(__DIR__ . '/../Models/User.php');
include_once __DIR__ . '/../Helper/InputHelper.php';
include_once __DIR__ . '/../Helper/Csrf.php';


class UserController extends BaseController
{
    function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        if (AuthHelper::checkAuth()) {
            header("location:/posts");
        }
        return $this->view('auth/login');
    }

    public function auth()
    {
        try {
            $params['email'] = InputHelper::email($_POST['email']);
            $params['password'] = InputHelper::str($_POST['password']);
        } catch (Exception $e) {
            $this->message('error', $e->getCode(), $e->getMessage());
            return header('location:/posts');
        }

        $user = new User();
        $result = $user->verifyUser($params['email'], $params['password']);

        if ($result) {
            session_regenerate_id(true);
            $_SESSION['user']['username'] = $result['username'];
            $_SESSION['user']['email'] = $result['email'];
            $_SESSION['user']['id'] = $result['id'];
            return header('Location:/posts');
        } else {
            return $this->message('error','406','Login fail!')->view('auth/login');
        }
    }

    public function logout() {
        unset($_SESSION['user']);
        return header('location:/auth/login');
    }

    public function register()
    {
        if (AuthHelper::checkAuth()) {
            return header("location:/posts");
        }
        return $this->view('auth/register');
    }

    public function store()
    {
        try {
            $params['email'] = InputHelper::email($_POST['email']);
            $params['username'] = InputHelper::str($_POST['username']);
            $params['phone_number'] = InputHelper::int($_POST['phone_number']);
            $params['password'] = InputHelper::str($_POST['password']);
            $params['confirm_password'] = InputHelper::str($_POST['confirm_password']);
        } catch (Exception $e) {
            $this->message('error', $e->getCode(), $e->getMessage());
            return header('location:/posts');
        }
        if ($params['password'] != $params['confirm_password']) {
            $this->message('error', '400', 'Password is not confirmed!')
                ->view('auth/register');
        }

        $user = new User;
        if ($user->isExisted($params['email'])) {
            return $this->message('error','400','User existed! Create failed!')
                ->view('auth/register');
        }
        $db = $user->db->database;
        try {
            $db->beginTransaction();
            $user->create($params);
            $db->commit();
            return $this->message('success','200','Account is created successfully!')
                ->view('auth/register');
        } catch (PDOException $e) {
            $db->rollBack();
            return $this->message('error','500','Cannot create account!')
                ->view('auth/register');
        }
    }
}
