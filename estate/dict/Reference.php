<?php 
namespace common\estate\dict;

use WS;
use common\core\ActiveRecord;

class Reference extends ActiveRecord
{
    const ALL_CACHE_KEY = 'field_reference-';

    public static function tableName()  
    {  
        return 'house_field_reference';
    }

    public static function mapForType($type)
    {
        static $caches = [];
        if(! isset($caches[$type])) {
            $type = strtolower($type);
            $allItems = self::all();
            $caches[$type] = array_change_key_case(isset($allItems[$type]) ? $allItems[$type] : []);
        }
        return $caches[$type];
    }

    public static function map($type, $field)
    {
        $type = strtolower($type);
        $field = strtoupper($field);

        $allItems = self::all();
        return isset($allItems[$type]) && isset($allItems[$type][$field]) ? $allItems[$type][$field] : [];
    }

    public static function get($type, $field, $value)
    {
        $allItems = self::all();

        if(strpos($value, ',') !== false) {
            $buildValues = [];
            foreach(explode(',', $value) as $v) {
                $buildValues[] = self::get($type, $field, $v);
            }
            return implode(',', $buildValues);
        }

        $type = strtolower($type);
        $field = strtoupper($field);
        $theValue = $allItems;
        foreach([$type, $field, $value] as $m) {
            if(isset($theValue[$m])) {
                $theValue = $theValue[$m];
            }
            else {
                $theValue = $value;
                break;
            }
        }

        return $theValue;
    }

    public static function all()
    {
        $lang = WS::$app->language;
        $cacheKey = self::ALL_CACHE_KEY.$lang;

        static $results = [];

        if(! empty($results)) return $results;
        
        $results = \WS::$app->cache->get($cacheKey, []);
        if(empty($results)) {
            $types = ['sf', 'cc', 'mf', 'ld', 'ci', 'bu', 'rn', 'mh'];
            $rows = self::find()->all();

            foreach($rows as $row) {
                if($lang == 'zh-CN' && $row->long_cn) {
                    $row->long = $row->long_cn;
                }

                foreach($types as $_type) {
                    if($row->short) 
                        $results[$_type][$row->field][$row->short] = $row->long;
                    if($row->medium)
                        $results[$_type][$row->field][$row->medium] = $row->long;
                }
            }
            \WS::$app->cache->set($cacheKey, $results);
        }

        return $results;
    }
}