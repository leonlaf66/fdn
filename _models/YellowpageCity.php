<?php
namespace models;

class YellowpageCity extends ActiveRecord
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