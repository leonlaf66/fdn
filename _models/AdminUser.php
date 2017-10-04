<?php
namespace models;

class AdminUser extends ActiveRecord
{
    public static function tableName()
    {
        return 'admin_user';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}