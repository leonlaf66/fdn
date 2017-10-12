<?php
namespace models;

class HouseIndex extends ActiveRecord
{
    public static function tableName()
    {
        return 'house_index';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}