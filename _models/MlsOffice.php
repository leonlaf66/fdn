<?php
namespace models;

class MlsOffice extends ActiveRecord
{
    public static function tableName()
    {
        return 'house_info_office';
    }

    public static function primaryKey()
    {
        return ['mls_number'];
    }
}