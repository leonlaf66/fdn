<?php
namespace models;

class FsStorage extends ActiveRecord
{
    public static function tableName()
    {
        return 'fs_storage';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}