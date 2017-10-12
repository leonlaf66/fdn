<?php
namespace models;

class SchoolDistrictSetting extends ActiveRecord
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