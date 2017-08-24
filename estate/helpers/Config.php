<?php
namespace common\estate\helpers;

class Config 
{
    public static function get($group, $defValue = null)
    {
        $file = __DIR__.'/../config/'.$group.'.php';
        if (file_exists($file)) {
            return include(__DIR__.'/../config/'.$group.'.php');
        }

        return $defValue;
    }
}