<?php
namespace models;

use WS;

class MemberHouseNewsletter extends ActiveRecord
{
    public $city;
    public $prop_type;
    public $price_range;
    public $bed_rooms;
    public $bath_rooms;
    public $notification_cycle;

    public static function tableName()
    {
        return 'house_member_favority';
    }

    public function getDataItems()
    {
        $results = [];
        $data = json_decode($this->data);
        foreach($data as $name=>$value) {
            if ($value !== '') {
                $label = $this->getAttributeLabel($name);
                $results[$label] = $this->getNamedValue($name, $value);
            }
        }
        return $results;
    }

    public function beforeSave($insert)
    {
        if($insert) {
            $this->created_at = date('Y-m-d H:i:s', time());
        }
        else {
            $this->updated_at = date('Y-m-d H:i:s', time());
        }
        $this->language = WS::$app->language;

        if($insert || is_null($this->next_task_at)) {
            $today = strtotime(date('Y-m-d', time()));
            if($this->notification_cycle == '1') {
                $this->next_task_at = date('Y-m-d', strtotime('+1 day', $today));
            }
            elseif($this->notification_cycle == '2') {
                $this->next_task_at = date('Y-m-d', strtotime('+1 week', $today));
            }
        }

        $this->data = json_encode([
            'city'=>$this->city,
            'prop_type' => $this->prop_type,
            'price_range'=>$this->price_range,
            'bed_rooms'=>$this->bed_rooms,
            'bath_rooms'=>$this->bath_rooms,
            'notification_cycle'=>$this->notification_cycle
        ]);

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $data = json_decode($this->data);
        foreach($data as $name=>$value) {
            $this->$name = $value;
        }
        return parent::afterFind();
    }
}