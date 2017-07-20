<?php
namespace common\customer\forms;

use WS;
use yii\base\Model;
use common\customer\Account;

class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirm_password;
    public $accept_agreed;

    public function rules()
    {
        return [
            [['username', 'email'], 'filter', 'filter'=>'trim'],
            [['username', 'email', 'password'], 'required'],
            [['username'], 'string', 'min'=>4, 'max'=>15],
            [['username'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => WS::t('account', 'Your username can only contain alphanumeric characters, underscores and dashes.')],
            [['username'], 'unique', 'targetClass'=>Account::className(), 'message'=>WS::t('account', 'This username already exists!')],
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
            'username'=>WS::t('account', 'User Name'),
            'email'=>WS::t('account', 'Email Address'),
            'password'=>WS::t('account', 'Password'),
            'confirm_password'=>WS::t('account', 'Confirm Password'),
            'accept_agreed'=>WS::t('account', '接受米乐居协议条款'),
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

    public function accountRegister()
    {
        $account = new Account();
        $account->username = $this->username;
        $account->email = $this->email;
        $account->password = md5($this->password);
        $account->auth_key = WS::$app->getSecurity()->generateRandomString();
        $account->access_token = WS::$app->security->generateRandomString();
        $account->created_at = date('Y-m-d H:i:s', time());
        $account->registration_ip = WS::$app->request->getUserIP();
        if($account->save()) {
            return $account;
        }
        return false;
    }
}
