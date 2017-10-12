<?php
namespace models;

class MemberProfile extends ActiveRecord
{
    public static function tableName()
    {
        return 'member_profile';
    }

    public static function primaryKey()
    {
        return ['user_id'];
    }
}