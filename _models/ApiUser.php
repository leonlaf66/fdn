<?php
namespace models;

class ApiUser extends ActiveRecord
{
    public static function tableName()
    {
        return 'api_user';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}