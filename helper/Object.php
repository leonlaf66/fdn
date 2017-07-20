<?php
namespace common\helper;

class Object
{
    public static function toArray($obj, $fields, $callable = null)
    {
        if(is_string($fields)) $fields = explode(',', $fields);
        if(!$callable) $callable = function($field, $obj) {return $obj->$field;};

        $arr = [];
        foreach($fields as $idx=>$field) {
            $arr[$idx] = $callable($field, $obj);
        }
        return $arr;
    }

    public static function map($rows, $callable)
    {
        $results = [];
        foreach($rows as $row) {
            $results[] = $callable($row);
        }
        return $results;
    }
}