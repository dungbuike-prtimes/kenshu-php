<?php
require_once "Database.php";

class Model
{
    protected $dbHandle;

    function __construct() {
        $this->dbHandle = new Database();
    }

}