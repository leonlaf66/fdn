<?php
namespace models;

class YellowPageCity extends ActiveRecord
{
    public static function tableName()
    {
        return 'yellow_page_city';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}