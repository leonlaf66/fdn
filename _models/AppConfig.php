<?php
namespace models;

class AppConfig extends ActiveRecord
{
    public static function tableName()
    {
        return 'app_configs';
    }

    public static function primaryKey()
    {
        return ['app_id', 'config_id'];
    }
}