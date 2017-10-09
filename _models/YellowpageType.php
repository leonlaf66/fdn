<?php
namespace models;

class YellowpageType extends ActiveRecord
{
    public static function tableName()
    {
        return 'yellow_page_type';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}