<?php
namespace models;

class Yellowpage extends \common\core\ActiveRecord
{
    public static function tableName()
    {
        return 'catalog_yellow_page';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}