<?php
namespace common\estate\helpers;

use WS;
use yii\helpers\ArrayHelper;

class Rets
{
    public static function result($search)
    {
        $ids = [];
        foreach($search->getModels() as $rets) {
            $ids[] = $rets->id;
        }
        return \common\estate\Rets::all($ids);
    }

    public static function getPhotoUrl($listNo, $n = 0, $w = 100, $h = 100)
    {
        return "http://media.mlspin.com/Photo.aspx?mls={$listNo}&n={$n}&w={$w}&h={$h}";
    }

    public static function fetchNameFromDict($dictConfigName, $value)
    {
        static $dicts = [];
        if (!isset($dicts[$dictConfigName])) {
            $dicts[$dictConfigName] = include(self::getConfigFile('dicts/'.$dictConfigName.'.php'));
        }
        return ArrayHelper::getValue($dicts[$dictConfigName], $value, $value);
    }

    public static function toStatusName($status)
    {
        if($status === 'SLD') {
            return \WS::$app->language === 'zh-CN' ? '已销售' : 'Sold';
        }
        if(\WS::$app->language === 'zh-CN') {
            return $status == 'NEW' ? '新房源' : '销售中';
        }
        return $status == 'NEW' ? 'New' : 'Active';
    }

    public static function buildLocation($d)
    {
        $propType = $d['prop_type'];

        $fields = in_array($propType, ['RN', 'CC']) ? [
            'street', 'unit_no', 'town', 'zip_code'
        ] : [
            'street', 'town', 'zip_code'
        ];

        $result = [];
        foreach($fields as $field) {
            $value = $d[$field];
            if($field == 'street') {
                $value = $d['street_num'].' '.ucwords(strtolower($d['street_name']));
            }
            if($field == 'town') {
                $value = self::fetchNameFromDict('cities', $d['town']);
            }
            if($field == 'zip_code'){
                $value = 'MA '.$d['zip_code'];
            }
            if($value) $result[] = $value;
        }

        return implode(' ', $result);
    }

    public static function buildMarketDays(& $d)
    {
        $datetime1 = date_create(ArrayHelper::getValue($d, 'list_date'));
        $datetime2 = date_create(date('Y-m-d', time()));

        $interval = date_diff($datetime1, $datetime2);

        return intval($interval->format('%a'));
    }

    public static function getConfigFile($name)
    {
        return __DIR__.'/../config/'.$name;
    }
}