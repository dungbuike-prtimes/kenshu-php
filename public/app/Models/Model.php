<?php
require_once "Database.php";

class Model
{
    protected $table;
    public $db;

    function __construct() {
        $this->db = new Database();
    }

}
