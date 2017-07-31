<?php

namespace common\customer\forms;

use WS;
use yii\base\Model;
use common\customer\Account;

/**
 * LoginForm is the model behind the login form.
 */
class ForgotPasswordForm extends Model
{
    public $email;
    public $isProcessed = false;

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'exist', 'targetClass'=>Account::className(),  'message' => tt('Account not exist!', '不存在的帐户!')]
        ];
    }

    public function attributeLabels()
    {
        return [
            'email'=>tt('Email Address', '邮件地址')
        ];
    }

    public function getAccount()
    {
        return Account::findByEmail($this->email);
    }
}
