<?php
namespace common\customer\forms;

use WS;
use yii\base\Model;
use common\customer\Account;

class ResetPasswordForm extends Model
{
    public $new_password;
    public $confirm_new_password;

    public function rules()
    {
        return [
            [['new_password', 'confirm_new_password'], 'required'],
            [['confirm_new_password'], 'validateConfirmPassword', 'skipOnEmpty'=>false]
        ];
    }

    public function attributeLabels()
    {
        return [
            'new_password'=>tt('New Password', '新密码'),
            'confirm_new_password'=>tt('Confirm new Password', '确认新密码')
        ];
    }

    public function validateConfirmPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->new_password !== $this->confirm_new_password) {
                $this->addError($attribute, WS::t('account',tt('Please make sure your passwords match.', '两次输入密码不一致!')));
            }
        }
    }
}
