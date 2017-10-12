<?php
namespace models;

class SchooldiStrictSetting extends ActiveRecord
{
    public static function tableName()
    {
        return 'schooldistrict_setting';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}