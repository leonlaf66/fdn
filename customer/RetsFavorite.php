<?php
namespace common\customer;

use WS;

class RetsFavorite extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'rets_favorites';
    }

    public static function findByUserId($userId)
    {
        return self::find()->where(['user_id'=>$userId])->orderBy('created_at', 'DESC')->limit(1000);
    }

    public function getRets($listNo=null)
    {
        if(! $listNo) $listNo = $this->list_no;
        return \common\estate\Rets::findOne($listNo);
    }

    public function getUser()
    {
        return $this->hasOne(\common\customer\Account::className(), ['id'=>'user_id'])->one();
    }

    public static function addOrRemove($listNo, $userId)
    {
        if(self::have($listNo, $userId)) {
            return self::remove($listNo, $userId);
        }

        return self::add($listNo, $userId);
    }

    public static function add($listNo, $userId)
    {
        if(self::have($listNo, $userId)) {
            return null;
        }

        $rets = \common\estate\Rets::findOne($listNo);
        if(! $rets) {
            return null;
        }

        $m = new self();
        $m->user_id = $userId;
        $m->list_no = $listNo;
        $m->property_type = $rets->prop_type;
        $m->created_at = date('Y-m-d H:i:s');

        return $m->save();
    }

    public static function remove($listNo, $userId)
    {
        if(!self::have($listNo, $userId)) {
            return null;
        }

        $item = self::findByListNo($listNo);
        if(! $item) {
            return null;
        }

        return $item->delete() > 0;
    }

    public static function findByListNo($listNo)
    {
        return self::find()->where(['list_no' => $listNo])->one();
    }

    public static function have($listNo, $userId)
    {
        return self::find()->where(['list_no'=>$listNo, 'user_id'=>$userId])->exists();
    }
}