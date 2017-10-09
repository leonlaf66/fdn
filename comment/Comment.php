<?php
namespace common\comment;

class Comment extends \yii\db\ActiveRecord
{
    public static function tableName()  
    {  
        return 'comment';
    }

    public function rules()
    {
        return [
            [['rating', 'comments'], 'required'],
            [['rating'], 'integer'],
            [['comments'], 'string', 'min'=>10, 'max'=>8000]
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