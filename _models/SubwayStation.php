<?php
namespace models;

class AppConfig extends \common\core\ActiveRecord
{
    public static function tableName()
    {
        return 'catalog_subway_station';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}