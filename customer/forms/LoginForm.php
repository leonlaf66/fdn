<?php
namespace common\customer\forms;

use WS;
use yii\base\Model;
use common\customer\Account;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email'], 'exist', 'targetClass'=>Account::className(),  'message' => tt('The account not exist!', '不存在的帐号!')],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email'=>tt('Email Address', '邮件地址'),
            'password'=>tt('Password', '密码'),
            'rememberMe' => tt('Remember me', '保持登陆')
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, tt('Incorrect email or password.', '不存在的邮件地址或密码!'));
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            
            WS::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);

            return $user;
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Account::findByEmail($this->email);
        }

        return $this->_user;
    }
}
