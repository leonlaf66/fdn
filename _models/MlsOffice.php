<?php
namespace models;

class MlsOffice extends \common\core\ActiveRecord
{
    public static function tableName()
    {
        return 'rets_mls_office';
    }

    public static function primaryKey()
    {
        return ['mls_number'];
    }
}