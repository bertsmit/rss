<?php

class Rubric
{
    protected $id;
    protected $name;

    function getId()
    {
        return $this->id;
    }
    
    function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    function getName()
    {
        return $this->name;
    }
    
    function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
}
