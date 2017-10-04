<?php
namespace models;

class MlsMember extends ActiveRecord
{
    public static function tableName()
    {
        return 'rets_mls_member';
    }

    public static function primaryKey()
    {
        return ['mls_id'];
    }
}