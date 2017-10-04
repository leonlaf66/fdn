<?php
namespace models;

class I18nMessage extends ActiveRecord
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