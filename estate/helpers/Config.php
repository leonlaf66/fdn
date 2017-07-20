<?php
namespace common\estate\helpers;

class Config 
{
    public static function get($group)
    {
        return include(__DIR__.'/../config/'.$group.'.php');
    }
}