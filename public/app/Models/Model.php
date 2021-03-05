<?php
require_once "Database.php";

class Model
{
    protected $table;
    protected $db;

    function __construct() {
        $this->db = new Database();
    }

}