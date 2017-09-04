<?php
namespace models;

class YellowpageType extends \common\core\ActiveRecord
{
    public static function tableName()
    {
        return 'catalog_yellow_page_types';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}