<?php
namespace models;

class MlsIndex extends ActiveRecord
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