<?php
namespace common\customer;

use WS;
use yii\helpers\ArrayHelper;

class RetsNewsletter extends \yii\db\ActiveRecord
{
    public $city;
    public $prop_type;
    public $price_range;
    public $bed_rooms;
    public $bath_rooms;
    public $notification_cycle;

    public static function tableName()
    { 
        return 'house_member_newsletter';
    }

    public static function t($message, $params=[])
    {
        return \WS::t('rets-nl', $message, $params);
    }

    public function attributeLabels()
    {
        return [
            'prop_type'=>tt('Type', '类型'),
            'name'=>tt('Name', '名称'),
            'city'=>tt('City', '城市'),
            'price_range'=>tt('Price Range($)', '价格区间(美元)'),
            'bed_rooms'=>tt('Beds', '卧室数'),
            'bath_rooms'=>tt('Baths', '浴室数'),
            'notification_cycle'=>tt('Cycle', '周期'),
            'created_at'=>tt('Created At', '创建时间')
        ];
    }

    public function rules()
    {
        return [
            [['name', 'prop_type', 'city', 'price_range', 'notification_cycle'], 'required'],
            [['prop_type'], 'in', 'range'=>['RN', 'SF', 'MF', 'CC', 'CI', 'BU', 'LD']],
            [['price_range'], 'validatePriceRange'],
            [['bed_rooms', 'bath_rooms'], 'number', 'min'=>0],
            [['notification_cycle'], 'in', 'range'=>[1,2]],
            [['city'], 'safe'],
            [['id', 'language', 'user_id', 'prop_type', 'data', 'next_task_at', 'created_at', 'updated_at'], 'safe']
        ];
    }

    public function validatePriceRange($attribute, $params)
    {
        $priceRange = $this->$attribute;
        $prices = explode('-', $priceRange);
        $errorMessage = self::t('Incorrect price range.');
        if(count($prices)!==2) {
            $this->addError($attribute, WS::t('account', $errorMessage));
            return;
        }

        if(! is_numeric($prices[0])) {
            $this->addError($attribute, WS::t('account', $errorMessage));
            return;
        }

        if(! is_numeric($prices[1])) {
            $this->addError($attribute, WS::t('account', $errorMessage));
            return;
        }

        if(intval($prices[0]) > intval($prices[1])) {
            $this->addError($attribute, WS::t('account', $errorMessage));
            return;
        }
    }

    public function getNamedValue($attribute)
    {
        $typeOptions = self::typeOptions();
        $cityOptions = self::cityOptions();
        $cycleOptions = self::cycleOptions();

        switch ($attribute) {
            case 'prop_type':
                return ArrayHelper::getValue($typeOptions, $this->prop_type);
            case 'city':
                return ArrayHelper::getValue($cityOptions, $this->city);
            case 'price_range':
                $s = explode('-', $this->$attribute);
                $s[0] = number_format($s[0], 0);
                $s[1] = number_format($s[1], 0);

                return tt('$'.$s[0].'&nbsp;-&nbsp;$'.$s[1], $s[0].'美元&nbsp;-&nbsp;'.$s[1].'美元');
            case 'bed_rooms':
            case 'bath_rooms':
                if($this->$attribute !== '0') {
                    return $this->$attribute . '+';
                }
                break;
            case 'notification_cycle':
                return ArrayHelper::getValue($cycleOptions, $this->notification_cycle);
            default:
                return $this->$attribute;
        };
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

    public static function findTasks()
    {
        //return self::find();
        return self::find()->andWhere('next_task_at<now()');
    }

    public function makeTaskStatus()
    {
        $this->last_task_at = date('Y-m-d', time()).' 00:00:00';

        $today = strtotime(date('Y-m-d', time()));
        if($this->notification_cycle == 1) {
            $this->next_task_at = date('Y-m-d', strtotime('+1 day', $today));
        }
        else {
            $this->next_task_at = date('Y-m-d', strtotime('+1 week', $today));
        }

        return $this->save();
    }

    public function getSearchResult()
    {
        $apply = [
            'prop_type'=>function($value, $query) {
                if($value !== '') {
                    $query->andWhere(['prop_type' => $value]);
                }
            },
            'city'=>function($cityCode, $query) {
                if($cityCode !== '') {
                    $query->andWhere(['town' => $cityCode]);
                }
            },
            'price_range'=>function($value, $query) {
                list($min, $max) = explode('-', $value);
                $min = intval($min); $max = intval($max);
                if($max == 0) $max = 9999999999;

                $query->andWhere(['between', 'list_price', $min, $max]);
            },
            'bed_rooms'=>function($value, $query) {
                if(intval($value) > 0) {
                    $query->andWhere(['>', 'no_bedrooms', intval($value)]);
                }
            },
            'bath_rooms'=>function($value, $query) {
                if(intval($value) > 0) {
                    $query->andWhere(['>', 'no_bathrooms', intval($value)]);
                }
            }
        ];

        $search = \common\estate\RetsIndex::search();
        $search->pagination->pageSize = 100;
        
        foreach($apply as $attribute=>$fn) {
            if($value = $this->$attribute) {
                $fn($value, $search->query);
            }
        }

        $today = strtotime(date('Y-m-d', time()));
        $startTime = date('Y-m-d H:i:s', strtotime('-1 week', $today));
        $endTime = date('Y-m-d H:i:s', time());

        $search->query->andWhere(['between', 'list_date', $startTime, $endTime]);

        return \common\estate\helpers\Rets::result($search);
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

    public static function cityOptions()
    {
        return \common\catalog\Town::mapOptions('short_name');
    }

    public static function typeOptions()
    {
        return \common\estate\Rets::propertyTypes();
    }

    public static function bedOptions()
    {
        return ['0'=>\WS::t('rets-nl', 'All'), '1'=>'1+', '2'=>'2+', '3'=>'3+', '4'=>'4+', '5'=>'5+'];
    }

    public static function bathOptions()
    {
        return ['0'=>\WS::t('rets-nl', 'All'), '1'=>'1+', '2'=>'2+', '3'=>'3+', '4'=>'4+', '5'=>'5+'];
    }

    public static function cycleOptions(){
        return ['1'=>tt('Daily', '每天'), '2'=>tt('Weekly', '每周')];
    }
}