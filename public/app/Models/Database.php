<?php
require_once __DIR__.'/../../config/database.php';
class Database
{
    private $dbHost = DB_HOST;
    private $dbUser = DB_USER;
    private $dbPass = DB_PASS;
    private $dbName = DB_NAME;
    private $dbPort = DB_PORT;

    private $error;
    public $database;
    private $statement;

    public function __construct()
    {
        $conn = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
//            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        );
        try {
            $this->database = new PDO($conn, 'root', 'root', $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $e;
        }
    }

    public function query($sql) {
        $this->statement = $this->database->prepare($sql);
    }

    public function bind($param, $value, $type) {
        switch (is_null($type)) {
            case is_int($value): {
                $type = PDO::PARAM_INT;
                break;
            }
            case is_bool($value): {
                $type = PDO::PARAM_BOOL;
                break;
            }
            case is_null($value): {
                $type = PDO::PARAM_NULL;
                break;
            }
            default:
                $type = PDO::PARAM_STR;
        }
        $this->statement->bindValue($param, $value, $type);
    }

    public function execute() {
        return $this->statement->execute();
    }

    public function all() {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first() {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function lastInsertedId() {
        return $this->database->lastInsertId();
    }

}