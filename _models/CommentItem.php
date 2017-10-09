<?php
namespace models;

class CommentPage extends ActiveRecord
{
    public static function tableName()
    {
        return 'comment_page';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}