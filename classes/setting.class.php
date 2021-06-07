<?php


class Setting
{
    protected $id;
    protected $setting;
    protected $value;

    function getId()
    {
        return $this->id;
    }

    function getSettingName()
    {
        return $this->setting;
    }
    
    function setSettingName($setting_name)
    {
        $this->setting = $setting_name;
        return $this;
    }
    
    function getValue()
    {
        return $this->value;
    }
    
    function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
