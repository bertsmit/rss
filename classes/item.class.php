<?php

class Item
{
    protected $id;
    protected $user_id;
    protected $rss;
    protected $title;
    protected $description;
    protected $rubric;
    protected $endDate;
    protected $startDate;
    protected $image;
    

    function getId()
    {
        return $this->id;
    }
    
    function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    function getUserId()
    {
        return $this->user_id;
    }
    
    function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }
    
    function getRss()
    {
        return $this->rss;
    }
    
    function setRss($rss)
    {
        $this->rss = $rss;
        return $this;
    }
    
    function getTitle()
    {
        return $this->title;
    }
    
    function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    function getDescription()
    {
        return $this->description;
    }
    
    function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    function getRubric()
    {
        return $this->rubric;
    }
    
    function setRubric($rubric)
    {
        $this->rubric = $rubric;
        return $this;
    }
    
    function getEndDate()
    {
        return $this->endDate;
    }
    
    function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }
    
    function getStartDate()
    {
        return $this->startDate;
    }
    
    function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }
    
    function getImage()
    {
        return $this->image;
    }
    
    function setImage($image)
    {
        $this->image = $image;
        return $this;
    }
    
}