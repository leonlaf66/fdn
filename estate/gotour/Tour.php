<?php
namespace common\estate\gotour;

use WS;

class Tour extends \yii\db\ActiveRecord
{
    CONST STATUS_UNCONFIRMED = 0;
    CONST STATUS_CONFIRMED = 1;
    CONST STATUS_EXPIRED = 3;

    public static function tableName()
    {
        return 'rets_tour';
    }

    public function rules()
    {
        return [
            [['date_start', 'date_end'], 'required']
        ];
    }

    public static function t($message, $params=[])
    {
        return \WS::t('tour', $message, $params);
    }

    public static function statusOptions()
    {
        return [
            self::STATUS_UNCONFIRMED=>tt('Unconfirmed', '未确认'),
            self::STATUS_CONFIRMED=>tt('Confirmed', '已确认')
        ];
    }

    public function attributeLabels()
    {
        return [
            'date_start'=>tt('Date Start', '开始时间'),
            'date_end'=>tt('Date End', '结束时间'),
            'status'=>tt('Status', '状态')
        ];
    }

    public function getRetsName()
    {
        $rets = $this->getRets();
        if($rets) {
            return $rets->title();
        }
        return '';
    }

    public function getRets()
    {
        static $retsArray = [];
        if(! isset($retsArray[$this->list_no])) {
            $retsArray[$this->list_no] = \common\estate\Rets::findOne($this->list_no);
        }
        return $retsArray[$this->list_no];
    }

    public function getRetsData($params=[])
    {
        $params = \yii\helpers\ArrayHelper::merge([
            'imageOptions'=>[
                'width'=>50,
                'height'=>50
            ]
        ], $params);

        $rets = $this->getRets();
        $imageOptions = $params['imageOptions'];

        return [
            'location'=>$rets->getLocation(),
            'image_url'=>$rets->getPhotoUrl($imageOptions['width'], $imageOptions['height']),
            'list_price'=>$rets->getPrice(),
            'status'=>$rets->getStatus()
        ];
    }

    public function getStatusName()
    {
        $status = self::statusOptions();
        if(! in_array($this->status, [self::STATUS_UNCONFIRMED, self::STATUS_CONFIRMED])) {
            $this->status = self::STATUS_UNCONFIRMED;
        }
        return $status[$this->status];
    }

    public function confirm()
    {
        $this->status = self::STATUS_CONFIRMED;
        return $this->save();
    }

    public static function findByUser($userId)
    {
        return self::find()->where(['user_id'=>$userId])->orderBy('date_start desc');
    }

    public static function findOneByUser($id, $userId)
    {
        return self::find()->where(['user_id'=>$userId, 'id'=>$id])->one();
    }

    public function getUser()
    {
        return $this->hasOne(\common\customer\Account::className(), ['id'=>'user_id']);
    }

    public function getUserData()
    {
        $user = $this->getUser()->one();

        return [
            'id'=>$user->id,
            'username'=>$user->username,
            'email'=>$user->email
        ];
    }
}