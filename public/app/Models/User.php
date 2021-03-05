<?php
require_once 'Model.php';

class User extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
    }

    public function auth($email, $password) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email, null);
        $user = $this->db->first();
        if ($user) {
            if (password_verify($password, $user['PASSWORD'])) {
                return $user;
            }
        } else {
            return null;
        }
    }

    public function create($params = []) {
        $this->db->query("INSERT INTO users (username, email, PASSWORD, phone_number) VALUES (:username, :email, :password, :phone_number )");
        $this->db->bind(':email', $params['email'], null);
        $this->db->bind(':username', $params['username'], null);
        $this->db->bind(':phone_number', $params['phone_number'], null);
        $this->db->bind(':password', password_hash($params['email'], PASSWORD_DEFAULT), null);
        return $this->db->execute();
    }

    public function isExisted($email) :bool {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email, null);
        $user = $this->db->first();
        return $user ? true : false;
    }
}