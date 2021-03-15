<?php


class Csrf extends Helper
{
    public function generateToken() {
        session_start();
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        $_SESSION['csrf_token'] = $csrf_token;
        return $csrf_token;
    }

    public function verify() {
        if (isset($_POST["csrf_token"])
            && $_POST["csrf_token"] === $_SESSION['csrf_token']) {
            return true;
        } else {
            return header('Location:/');
        }

    }

}