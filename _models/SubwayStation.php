<?php
namespace models;

class AppConfig extends ActiveRecord
{
    public static function tableName()
    {
        return 'subway_station';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}