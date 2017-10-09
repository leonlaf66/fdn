<?php
namespace models;

class CoreConfigData extends ActiveRecord
{
    public static function tableName()
    {
        return 'site_setting';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}