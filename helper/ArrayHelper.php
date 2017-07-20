<?php
namespace common\helper;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    public static function build($arr, $callable)
    {
        $newArr = [];
        foreach($arr as $idx=>$d) {
            $newArr[$idx] = $callable($d);
        }
        return $newArr;
    }

    public static function index($array, $key, $value = null)
    {
        if (!$value) {
            return parent::index($array, $key);
        }

        $result = [];
        foreach ($array as $element) {
            $key = static::getValue($element, $key);
            $result[$key] = static::getValue($element, $value);
        }

        return $result;
    }

    public static function strToRange($value, $separator='-', $callable)
    {
        $result = [];

        if(is_array($value) && count($value) === 1) {
            $result = [$value, $value + 100000000];
        }
        elseif(is_string($value)) {
            if(! $callable) $callable = function($d) {return $d;};

            $parts = explode('-', $value);
            $result[0] = $callable($parts[0]);
            if(count($parts) === 1) {
                $result[1] = $callable($range[0]) + 10000000;
            }
            else {
                $result[1] = $callable($parts[1]);
            }
        }

        return $result;
    }

    public static function entityMap($arr, $keyFieldName)
    {
        $resultArr = [];
        foreach($arr as $row) {
            $key = $row[$keyFieldName];
            $resultArr[$key] = $row;
        }
        return $resultArr;
    }
}