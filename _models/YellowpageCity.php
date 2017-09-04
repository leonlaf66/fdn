<?php
namespace models;

class YellowpageCity extends \common\core\ActiveRecord
{
    public static function tableName()
    {
        return 'catalog_yellow_page_cities';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}