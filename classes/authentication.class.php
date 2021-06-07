<?php

require_once "core.class.php";
require_once "user.class.php";

class Authentication
{
    private $core;

    function __construct()
    {
        $this->core = new Core();
    }

    function validatePassword($name, $password)
    {
        $user = $this->core->getUserByName($name);
        $salt = $user->getSalt();
        $userpassword = md5(md5($salt).md5($password));
        $sqlpassword = $user->getPassword();

        if ($userpassword === $sqlpassword) {
            return true;
        } 
        else {
            return false;
        }  
    }

    function logout($id) {
        $user = $this->core->getUserById($id);

        unset($_SESSION["userId"]);
    }

}
