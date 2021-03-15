<?php
include_once '../Helper/InputHelper.php';

class BaseController
{
    protected $view;

    function __construct()
    {
    }


    public function view($view, $data = [])
    {
        require_once __DIR__ . "/../Views/" . $view . ".php";
        return $this;
    }

    public function message($type, $status, $message)
    {
        $_SESSION['type'] = $type;
        $_SESSION['message'] = $message;
        $_SESSION['status'] = $status;
        return $this;
    }
}

