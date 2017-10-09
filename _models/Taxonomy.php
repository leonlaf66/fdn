<?php
namespace models;

class Taxonomy extends ActiveRecord
{
    public static function tableName()
    {
        return 'taxonomy';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}