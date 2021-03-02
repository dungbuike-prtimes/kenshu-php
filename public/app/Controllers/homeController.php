<?php
require_once "BaseController.php";

class homeController extends BaseController
{
    public function index() {
        return $this->view("home/index");
    }
}