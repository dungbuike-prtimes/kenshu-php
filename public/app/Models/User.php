<?php
require_once 'Model.php';

class User extends Model
{
    public $email;
    private $password;
    private $user;

    public function auth($email, $password) {
        $this->dbHandle->query("SELECT * FROM users WHERE email = :email");
        $this->dbHandle->bind(':email', $email, null);
        $this->user = $this->dbHandle->first();
        if ($this->user) {
            if (password_verify($password, $this->user['PASSWORD'])) {
                return $this->user;
            }
        } else {
            return null;
        }
    }

    public function create($params = []) {
        $this->dbHandle->query("INSERT INTO users (username, email, PASSWORD, phone_number) VALUES (:username, :email, :password, :phone_number )");
        $this->dbHandle->bind(':email', $params['email'], null);
        $this->dbHandle->bind(':username', $params['username'], null);
        $this->dbHandle->bind(':phone_number', $params['phone_number'], null);
        $this->dbHandle->bind(':password', password_hash($params['email'], PASSWORD_DEFAULT), null);
        return $this->dbHandle->execute();
    }

    public function isExisted($email) {
        $this->dbHandle->query("SELECT * FROM users WHERE email = :email");
        $this->dbHandle->bind(':email', $email, null);
        $this->user = $this->dbHandle->first();
        return $this->user ? true : false;
    }
}