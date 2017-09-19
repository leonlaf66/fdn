<?php
namespace common\customer;

class UserIdentity extends \common\customer\Account
{
    public function getName()
    {
        $name = (new \yii\db\Query())
            ->select('name')
            ->from('user_profile')
            ->where(['user_id' => $this->id])
            ->scalar();

        if (! $name) {
            $name = explode('@', $this->email)[0];
        }

        return $name;
    }
}