<?php
namespace models;

class MemberHouseTour extends ActiveRecord
{
    CONST STATUS_UNCONFIRMED = 0;
    CONST STATUS_CONFIRMED = 1;
    CONST STATUS_EXPIRED = 3;

    public static function tableName()
    {
        return 'house_member_tour';
    }

    public function rules()
    {
        return [
            [['date_start', 'date_end'], 'required']
        ];
    }

    public static function statusOptions()
    {
        return [
            self::STATUS_UNCONFIRMED=>tt('Unconfirmed', '未确认'),
            self::STATUS_CONFIRMED=>tt('Confirmed', '已确认')
        ];
    }

    public function getRets($listNo=null)
    {
        if(! $listNo) $listNo = $this->list_no;

        if ($this->area_id === 'ma') {
            return \common\estate\Rets::findOne($listNo);
        } else {
            return \common\listhub\estate\House::findOne($listNo);
        }
    }

    public function confirm()
    {
        $this->status = self::STATUS_CONFIRMED;
        return $this->save();
    }
}