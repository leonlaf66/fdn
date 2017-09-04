<?php
namespace models;

class AdminUser extends \common\core\ActiveRecord
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