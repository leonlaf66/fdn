<?php
namespace models;

class HouseItem extends ActiveRecord
{
    public static function tableName()
    {
        return 'house_items';
    }

    public static function primaryKey()
    {
        return ['state', 'id'];
    }
}