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
            [['email'], 'exist', 'targetClass'=>Account::className(),  'message' => WS::t('account', 'The account not exist!')],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email'=>WS::t('account', 'Email Address'),
            'password'=>WS::t('account', 'Password')
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, WS::t('account', 'Incorrect email or password.'));
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
