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
    public $account_id;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    public function rules()
    {
        return [
            [['account_id', 'password'], 'required'],
            //[['email'], 'exist', 'targetClass'=>Account::className(),  'message' => tt('The account not exist!', '不存在的帐号!')],
            [['account_id'], 'validateAccountId'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'account_id'=>tt('Email Address/Phone Number', '邮件地址/手机号码'),
            'password'=>tt('Password', '密码'),
            'rememberMe' => tt('Remember me', '保持登陆')
        ];
    }

    public function validateAccountId($attribute, $params)
    {
        if(! Account::find()->where('email=:id or phone_number=:id', [':id' => $this->$attribute])->exists()) {
            $this->addError($attribute, tt('The account not exist!', '不存在的帐号!'));
            return false;
        }
        return true;
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, tt('Incorrect account or password.', '不存在的帐号或密码!'));
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
     * Finds user by [[account_id]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Account::findByAid($this->account_id);
        }

        return $this->_user;
    }
}
