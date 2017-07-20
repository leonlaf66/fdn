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
            'new_password'=>WS::t('account','New Password'),
            'confirm_new_password'=>WS::t('account','Confirm new Password')
        ];
    }

    public function validateConfirmPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->new_password !== $this->confirm_new_password) {
                $this->addError($attribute, WS::t('account','Please make sure your passwords match.'));
            }
        }
    }
}
