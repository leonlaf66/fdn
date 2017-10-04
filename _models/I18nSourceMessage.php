<?php
namespace models;

class I18nSourceMessage extends ActiveRecord
{
    public static function tableName()
    {
        return 'i18n_source_message';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}