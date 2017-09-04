<?php
namespace models;

class I18nMessage extends \common\core\ActiveRecord
{
    public static function tableName()
    {
        return 'i18n_message';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}