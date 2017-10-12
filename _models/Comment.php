<?php
namespace models;

class Comment extends ActiveRecord
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
            [['comments'], 'string', 'min'=>2, 'max'=>8000]
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