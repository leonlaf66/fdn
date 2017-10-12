<?php
namespace models;

class Storage extends ActiveRecord
{
    public static function tableName()
    {
        return 'storage';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}