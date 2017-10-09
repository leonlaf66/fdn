<?php
namespace models;

class FsStorage extends ActiveRecord
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