<?php
namespace models;

class DictTown extends \common\core\ActiveRecord
{
    public static function tableName()
    {
        return 'dict_town';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}