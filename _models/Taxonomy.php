<?php
namespace models;

class Taxonomy extends \common\core\ActiveRecord
{
    public static function tableName()
    {
        return 'catalog_taxonomy';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}