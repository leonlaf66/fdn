<?php
namespace models;

class MlsMember extends ActiveRecord
{
    public static function tableName()
    {
        return 'house_info_member';
    }

    public static function primaryKey()
    {
        return ['mls_id'];
    }
}