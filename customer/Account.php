<?php
namespace common\customer;

use WS;

class Account extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            [['password'], 'string', 'max' => 32],
            [['auth_key'], 'string', 'max' => 100],
            [['access_token'], 'string', 'max' => 100],
            [['confirmed_at', 'blocked_at', 'registration_ip', 'created_at', 'updated_at', 'flags'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email Address',
            'password' => 'Password',
            'auth_key' => 'AuthKey',
            'access_token' => 'AccessToken',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by email
     *
     * @param  string      $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
          $user = self::find()
            ->where(['email' => $email])
            ->asArray()
            ->one();

            if($user){
            return new static($user);
        }

        return null;
    }

    public function getIsConfirmed()
    {
        return $this->confirmed_at !== null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    public function sendConfirmEmail($url)
    {
        return WS::$app->mailer->compose('account/confirm', ['user' => $this, 'url'=>$url])
            ->setTo($this->email)
            ->setSubject('Account Confirm')
            ->send();
    }

    public function sendNewslatterEmail($subjedt, $template, $results)
    {
        return WS::$app->mailer->compose($template, $results)
            ->setTo($this->email)
            ->setSubject($subjedt)
            ->send();
    }

    public function sendRetrievePasswordEmail($url)
    {
        return WS::$app->mailer->compose('account/forgot-pwd', ['user' => $this, 'url'=>$url])
            ->setTo($this->email)
            ->setSubject('Forgot Password')
            ->send();
    }

    public function resetPassword($password)
    {
        $this->password = md5($password);
        return $this->save();
    }

    public function getProfile()
    {
        $profile = $this->hasOne(Profile::className(), ['user_id'=>'id'])->one();
        if(! $profile) {
            $profile = new Profile();
            $profile->user_id = $this->id;
        }

        return $profile; 
    }
}