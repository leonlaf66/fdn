<?php
namespace models;

class SubwayLine extends ActiveRecord
{
    public static function tableName()
    {
        return 'catalog_subway_line';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}