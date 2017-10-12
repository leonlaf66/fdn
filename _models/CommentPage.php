<?php
namespace models;

class CommentPage extends ActiveRecord
{
    public static function tableName()
    {
        return 'comment_page';
    }

    public function getComment()
    {
        return $this->getComments();
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['page_id' => 'id'])->orderBy('id DESC');
    }

    public function getUser()
    {
        return $this->hasOne(\common\customer\Account::className(), ['user_id' => 'id']);
    }
}