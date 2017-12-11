<?php
namespace common\estate\gotour;

use WS;

class Tour extends \models\MemberHouseTour
{
    public static function t($message, $params=[])
    {
        return \WS::t('tour', $message, $params);
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

    public function getRets($listNo = null)
    {
        if (! $this->list_no) $this->list_no = $listNo;

        static $retsArray = [];
        if(! isset($retsArray[$this->list_no])) {
            if ($this->area_id === 'ma') {
                $retsArray[$this->list_no] = \common\estate\Rets::findOne($this->list_no);
            } else {
                $retsArray[$this->list_no] = \common\listhub\estate\House::findOne($this->list_no);
            }
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
        $status = static::statusOptions();
        if(! in_array($this->status, [static::STATUS_UNCONFIRMED, static::STATUS_CONFIRMED])) {
            $this->status = static::STATUS_UNCONFIRMED;
        }

        return $status[is_null($this->status) ? static::STATUS_UNCONFIRMED : $this->status];
    }

    public static function findByUser($userId)
    {
        return static::find()->where(['user_id'=>$userId])->orderBy('date_start desc');
    }

    public static function findOneByUser($id, $userId)
    {
        return static::find()->where(['user_id'=>$userId, 'id'=>$id])->one();
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