<?php
namespace common\catalog;

class Town extends \common\core\ActiveRecord
{
    public $name_en = null;

    public static function tableName()  
    {  
        return 'town';
    }

    // 以name, name_cn,short_name,tax_num,zip_code为键的城市列表数据
    public static function fetchAllData($stateId = 'MA')
    {
        static $data = [];

        $zips = self::getAllZipCodes();
        if (empty($data)) {
            // 编码列表
            $allItems = self::find()->where(['state'=>$stateId])->all();
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
            foreach ($zips as $item) {
                $zip = $item['zip'];
                $cityName = $item['city_name'];

                if (isset($data[$cityName])) {
                    $data[$zip] = $data[$cityName];
                }
            }
        }

        return $data;
    }

    public static function searchKeywords($words, $stateId = 'MA')
    {
        return static::find()->where([
            'state' => $stateId
        ])->andWhere('name=:nm or name_cn=:nm', [
            ':nm' => $words
        ])->one();
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

    public static function get($code, $field='name')
    {
        static $data = [];
        $stateId = \WS::$app->stateId ?? 'MA';
        if(! isset($data[$stateId])) {
            foreach(self::find(['state'=>$stateId])->all() as $m) {
                $data[$stateId][$m->short_name] = $m;
            }
        }
        return isset($data[$stateId][$code]) ? $data[$stateId][$code]->$field : '';
    }

    public static function mapOptions($idField = 'id')
    {
        $citys = self::find()->where(['state'=>'MA'])->all();
        return \common\helper\ArrayHelper::index($citys, $idField, tt('name', 'name_cn'));
    }

    protected static function getAllZipCodes()
    {
        static $zips = [];
        if (empty($zips)) {
            $zips = Zipcode::find()->all();
        }
        return $zips;
    }
}