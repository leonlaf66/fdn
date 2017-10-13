<?php
namespace models;

class SiteSetting extends ActiveRecord
{
    protected static $_data = [];
    
    public static function tableName()
    {
        return 'site_setting';
    }

    public static function getValue($path, $defValue=null)
    {
        if(empty(self::$_data)) {
            self::$_data = self::_loadAllData();
        }
        return isset(self::$_data[$path]) ? 
            self::$_data[$path]
            :
            $defValue;
    }

    public static function getJson($path, $defValue=null)
    {
        if(empty(self::$_data)) {
            self::$_data = self::_loadAllData();
        }
        return isset(self::$_data[$path]) ? 
            json_decode(self::$_data[$path])
            :
            $defValue;
    }

    public static function get($path, $defValue=null)
    {
        return self::getValue($path, $defValue);
    }

    protected static function _loadAllData()
    {
        $data = [];

        $items = self::find()->all();
        foreach($items as $item) {
            $data[$item->path] = $item->value;
        }
        return $data;
    }
}