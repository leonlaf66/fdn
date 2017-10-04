<?php
namespace models;

class CommentPage extends ActiveRecord
{
    public static function tableName()
    {
        return 'comments_page';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}