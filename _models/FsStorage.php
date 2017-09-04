<?php
namespace models;

class FsStorage extends \common\core\ActiveRecord
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