<?php
namespace common\comment;

class CommentPage extends \yii\db\ActiveRecord
{
	public static function tableName()  
    {  
        return 'comments_page';
    }

    public function getComment()
    {
        return $this->hasMany(Comment::className(), ['page_id' => 'id'])->orderBy('id asc');
    }
}