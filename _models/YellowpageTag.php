<?php
namespace models;

class YellowpageTag extends ActiveRecord
{
    public static function tableName()
    {
        return 'yellow_page_tag';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}