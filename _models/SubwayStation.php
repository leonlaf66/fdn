<?php
namespace models;

class AppConfig extends ActiveRecord
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