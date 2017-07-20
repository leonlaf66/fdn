<?php
namespace common\catalog\subway;

class Line extends \common\core\ActiveRecord
{
    protected static $_items = null;
    public $stations = [];

    public static function tableName()  
    {  
        return 'catalog_subway_line';
    }

    public static function getMapOptions()
    {
        if(is_null(self::$_items)) {
            self::$_items = self::find()->orderBy(['sort_order'=>SORT_ASC])->all();
        }

        $optionMap = [];
        foreach(self::$_items as $item) {
            $optionMap[$item['code']] = $item;
        }

        return $optionMap;
    }

    public static function findName($id)
    {
        $findParams = is_numeric($id) ? $id : ['code'=>$id];

        $item = self::find($findParams)->findOne();
        if($item && $item->id) {
            return $item->name;
        }

        return null;
    }
}