<?php

class User {
    
    protected $id;
    protected $name;
    protected $email;
    protected $salt;
    protected $password;
    protected $admin;
    protected $permissions;

	function getId()
    {
        return $this->id;
    }
    
    function setId($id){
        $this->id = $id;
        return $this;
    }

    function getName()
    {
        return $this->name;
    }

    function setName($name) {
        $this->name = $name;
        return $this;
    }

    function getEmail()
    {
        return $this->email;
    }

    function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    function getSalt()
    {
        return $this->salt;
    }

    function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }

    function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    function getPassword()
    {
        return $this->password;
    }
    
    function setAdmin($admin)
    {
        $this->admin = $admin;
        return $this;
    }

    function getAdmin()
    {
        return $this->admin;
    }
    
    function setPermissions($permissions)
    {
        $this->permissions = $permissions;
        return $this;
    }

    function getPermissions()
    {
        return $this->permissions;
    }


}