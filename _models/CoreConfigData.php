<?php
namespace models;

class CoreConfigData extends \common\core\ActiveRecord
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