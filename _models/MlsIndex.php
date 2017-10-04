<?php
namespace models;

class MlsIndex extends ActiveRecord
{
    public static function tableName()
    {
        return 'rets_mls_index';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}