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
            [['email'], 'exist', 'targetClass'=>Account::className(),  'message' => WS::t('account','Account not exist!')]
        ];
    }

    public function attributeLabels()
    {
        return [
            'email'=>WS::t('account','Email Address')
        ];
    }

    public function getAccount()
    {
        return Account::findByEmail($this->email);
    }
}
