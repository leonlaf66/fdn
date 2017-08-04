<?php
namespace common\customer\account;

use WS;

class ModifyPasswordForm extends \yii\base\Model
{
    public $user_id;
    public $old_password;
    public $password;
    public $confirm_password;
    public $result = null;

    public function rules()
    {
        return [
            [['old_password'], 'checkOldPassword', 'skipOnEmpty'=>false],
            [['password', 'confirm_password'], 'string', 'min'=>6],
            [['confirm_password'], 'validatePassword', 'skipOnEmpty'=>false],
            [['user_id'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        $t = \WS::lang('account', true);

        return [
            'old_password'=>tt('Old Password', '旧密码'),
            'password'=>tt('New Password', '新密码'),
            'confirm_password'=>tt('Confirm Password', '确认新密码')
        ];
    }

    public function checkOldPassword($attribute, $params)
    {
        if (! $this->hasErrors()) {
            $account = \common\customer\Account::findIdentity($this->user_id);
            if(! $account->validatePassword($this->old_password)) {
                $this->addError($attribute, WS::t('account', 'Please make sure your old passwords is corrected.'));
            }
        }
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->password !== $this->confirm_password) {
                $this->addError($attribute, WS::t('account', 'Please make sure your passwords match.'));
            }
        }
    }

    public function modifyPassword()
    {
        $account = \common\customer\Account::findIdentity($this->user_id);
        return $this->result = $account->resetPassword($this->password);
    }
}