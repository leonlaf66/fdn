<?php
namespace models;

class YellowpageTag extends \common\core\ActiveRecord
{
    public static function tableName()
    {
        return 'catalog_yellow_page_tags';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}