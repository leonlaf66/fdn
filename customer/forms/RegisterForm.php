<?php
namespace common\customer\forms;

use WS;
use yii\base\Model;
use common\customer\Account;

class RegisterForm extends Model
{
    public $email;
    public $password;
    public $confirm_password;
    public $accept_agreed;

    public function rules()
    {
        return [
            [['email'], 'filter', 'filter'=>'trim'],
            [['password'], 'required'],
            [['accept_agreed'], 'validateProtocol', 'skipOnEmpty'=>false],
            [['email'], 'email'],
            [['email'], 'string', 'min'=>6, 'max'=>50],
            [['email'], 'unique', 'targetClass'=>Account::className(), 'message'=>WS::t('account', 'This email already exists!')],
            [['password'], 'string', 'min'=>6],
            [['confirm_password'], 'validatePassword', 'skipOnEmpty'=>false],
            [['accept_agreed'], 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'email'=>tt('Email Address', '邮箱地址'),
            'password'=>tt('Password', '密码'),
            'confirm_password'=>tt('Confirm Password', '确认密码'),
            'accept_agreed'=>tt('Accept USLEJU policy', '接受米乐居协议条款'),
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->password !== $this->confirm_password) {
                $this->addError($attribute, WS::t('account', 'Please make sure your passwords match.'));
            }
        }
    }

    public function validateProtocol($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if(! $this->accept_agreed) {
                $this->addError($attribute, tt('You need access USLEJU policy!', '必须接受米乐居条款!'));
            }
        }
    }

    public function accountRegister()
    {
        $account = new Account();
        $account->email = $this->email;
        $account->password = md5($this->password);
        $account->auth_key = WS::$app->getSecurity()->generateRandomString();
        $account->access_token = WS::$app->security->generateRandomString();
        $account->created_at = date('Y-m-d H:i:s', time());
        $account->updated_at = $account->created_at;
        $account->registration_ip = WS::$app->request->getUserIP();
        if($account->save()) {
            return $account;
        }
        return false;
    }
}
