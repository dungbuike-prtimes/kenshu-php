<?php


class BaseController
{
    protected $model;
    protected $view;

    function __construct() {

    }


    public function view($view, $data = []) {
        require_once __DIR__."/../Views/". $view .".php";
    }
}

