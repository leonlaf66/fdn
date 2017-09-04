<?php
namespace models;

class CommentPage extends \common\core\ActiveRecord
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