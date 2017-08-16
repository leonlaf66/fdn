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
            [['email'], 'validateAccountId'],
            //[['email'], 'exist', 'targetClass'=>Account::className(),  'message' => tt('Account not exist!', '不存在的帐户!')]
        ];
    }

    public function validateAccountId($attribute, $params)
    {
        if(! Account::find()->where('username=:id or email=:id or phone_number=:id', [':id' => $this->$attribute])->exists()) {
            $this->addError($attribute, tt('The account not exist!', '不存在的帐号!'));
            return false;
        }
        return true;
    }

    public function attributeLabels()
    {
        return [
            'email'=>tt('Username/Email Address/Phone Number', '用户名/邮件地址/手机号码')
        ];
    }

    public function getAccount()
    {
        return Account::findByEmail($this->email);
    }
}
