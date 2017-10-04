<?php
namespace models;

class CoreConfigData extends ActiveRecord
{
    public static function tableName()
    {
        return 'core_config_data';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}