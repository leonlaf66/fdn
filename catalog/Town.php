<?php
namespace common\catalog;

class Town extends \common\core\ActiveRecord
{
    public $name_en = null;

    public static function tableName()  
    {  
        return 'dict_town';
    }

    public static function fetchAllData()
    {
        static $data = [];
        static $zips = [];
        if (empty($data)) {
            // 编码列表
            $stateCode = \WS::$app->stateCode;
            $allItems = self::find()->where(['state'=>$stateCode])->all();
            foreach ($allItems as $item) {
                foreach([(\WS::isChinese() ? 'name_cn' : 'name'), 'short_name', 'tax_num'] as $fieldId) {
                    $key = $item[$fieldId];
                    if ($key) {
                        $data[$key] = [
                            'name_en' => $item->name,
                            'name' => \WS::isChinese() ? $item->name_cn : $item->name,
                            'short_name' => $item->short_name,
                            'tax_num' => $item->tax_num
                        ];
                    }
                }
            }

            // 针对邮编
            if (empty($zips)) {
                $zips = \common\estate\helpers\Config::get('dicts/zip')['zip_code'];
            }
            foreach ($zips as $zip => $item) {
                $cityName = $item['city'];
                if (isset($data[$cityName])) {
                    $data[$zip] = $data[$cityName];
                }
            }
        }

        return $data;
    }

    public static function getMapValue($group, $valueField)
    {
        $data = self::fetchAllData();
        if (! isset($data[$group])) return null;
        if (! isset($data[$group][$valueField])) return null;
        return $data[$group][$valueField];
    }

    public static function getQueuedDefaultCitys()
    {
        $citys = self::find()->where(['state'=>'MA'])->all();
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $groupNames = array();
        foreach(str_split($letters, 3) as $group) {
            foreach(str_split($group) as $letter) {
                $groupNames[$letter] = $group;
            }
        }
        
        $resultArray = array();
        foreach($citys as $city) {
            $c = strtoupper(substr($city->name, 0, 1));
            $group = $groupNames[$c];

            $resultArray[$group][] = $city;
        }
        return $resultArray;
    }

    public static function all()
    {
        return self::find()->where(['state'=>\WS::$app->stateCode])->all();
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['name'] = 'name';
        if(\WS::$app->language == 'zh-CN') {
            $fields['name_en'] = 'name_en';
            unset($fields['name_cn']);
        }
        return $fields;
    }

    public function afterFind()
    {
        if(\WS::$app->language == 'zh-CN') {
            $this->name_en = $this->name;
            if($this->name_cn)
                $this->name = $this->name_cn;
        }
        return parent::afterFind();
    }

    public static function get($code, $field='name')
    {
        static $data = [];
        $stateCode = \WS::$app->stateCode;
        if(! isset($data[$stateCode])) {
            foreach(self::find(['state'=>$stateCode])->all() as $m) {
                $data[$stateCode][$m->short_name] = $m;
            }
        }
        return isset($data[$stateCode][$code]) ? $data[$stateCode][$code]->$field : '';
    }

    public static function map()
    {
        return [];
    }
}