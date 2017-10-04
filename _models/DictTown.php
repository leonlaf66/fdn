<?php
namespace models;

class DictTown extends ActiveRecord
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