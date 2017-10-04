<?php
namespace models;

class News extends ActiveRecord
{
    public static function tableName()
    {
        return 'news';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}