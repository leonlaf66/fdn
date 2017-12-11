<?php
namespace common\listhub\estate;

class Setting
{
    protected static $cache = [];

    public static function get($fileId, $key, $defValue = null)
    {
        if (!isset(static::$cache[$fileId])) {
            static::$cache[$fileId] = include(__DIR__.'/etc/'.$fileId.'.php');
        }

        return static::$cache[$fileId][$key] ?? $defValue ;
    }
}