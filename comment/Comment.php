<?php
namespace common\comment;

class Comment extends \yii\db\ActiveRecord
{
    public static function tableName()  
    {  
        return 'comments_item';
    }

    public function rules()
    {
        return [
            [['page_id', 'rating', 'comments'], 'required'],
            [['page_id', 'rating'], 'integer'],
            [['comments'], 'string', 'max'=>8000]
        ];
    }

    public function getId()
    {
    	return $this->entity_id;
    }

    public function getUser()
    {
        return \common\customer\Account::findOne($this->user_id);
    }
}