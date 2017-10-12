<?php
namespace models;

use common\helper\ArrayHelper;

class SchooldiStrict extends ActiveRecord
{
    public $jsonData = [];

    public static function tableName()  
    {  
        return 'schooldistrict';
    }

    public static function xFind()
    {
        return self::find()->orderBy(['sort_order'=>'ASC']);
    }

    public static function allCodes()
    {
        static $codes = [];

        if (! empty($codes)) return $codes;

        $items = self::xFind()->all();

        foreach($items as $item) {
            $code = $item->getItemData('code');
            $itemCodes = explode('/', $code);
            foreach ($itemCodes as $code) {
                $codes[] = $code;
            }
        }

        return $codes;
    }

    public static function dictOptions()
    {
        $items = self::xFind()->all();
        $items = array_map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->getItemData('code'),
                'name' => \WS::isChinese() && $item->getItemData('name')[1] ? $item->getItemData('name')[1] : $item->getItemData('name')[0]
            ];
        }, $items);

        $items = array_column($items, null, 'id');

        return $items;
    }

    public function afterFind()
    {
        $this->jsonData = json_decode($this->json);
        return parent::afterFind();
    }

    public function getItemData($name, $defValue = null)
    {
        if (! property_exists($this->jsonData, $name)) return $defValue;
        return ArrayHelper::getValue($this->jsonData, $name, $defValue);
    }

    public function getSummary($name)
    {
        $sql = 'select data from schooldistrict_setting where code=:code and path=:name';
        $value = \WS::$app->db->createCommand($sql)
            ->bindValue(':code', $this->code)
            ->bindValue(':name', $name)
            ->queryScalar();

        return json_decode($value);
    }

    public function __get($name)
    {
        if(in_array($name, ['id', 'property', 'json', 'sort_order', 'code'])) {
            return parent::__get($name);
        }

        $getters = $this->getters();
        if (isset($getters[$name])) {
            return ($getters[$name])($this, $name);
        }

        return $this->getItemData($name);
    }

    public function getters()
    {
        return [
            'name' => function ($m, $name) {
                $names = $m->getItemData($name, ['', '']);
                return \WS::langText($names[0], $names[1]);
            },
            'description' => function ($m, $name) {
                $texts = $m->getItemData($name, ['', '']);
                $content = \WS::langText($texts[0], $texts[1]);
                if (strlen($content) === 0) {
                    $content = $texts[0];
                }
                if (strlen($content) === 0) {
                    $content = $texts[1];
                }
                return $content;
            },
            'advantage' => function ($m, $name) {
                $texts = $m->getItemData($name, ['', '']);
                $content = \WS::langText($texts[0], $texts[1]);
                if (strlen($content) === 0) {
                    $content = $texts[0];
                }
                if (strlen($content) === 0) {
                    $content = $texts[1];
                }
                return $content;
            },
            'environments' => function ($m, $name) {
                $environments = $m->getItemData($name, []);
                return array_map(function ($env) {
                    if (! property_exists($env, 'name_cn')) $env->name_cn = null;
                    $env->name = \WS::langText($env->name, $env->name_cn);

                    if (! property_exists($env, 'description_cn')) $env->description_cn = null;
                    $env->description = \WS::langText($env->description, $env->description_cn);
                    return $env;
                }, $environments);
            },
            'features' => function ($m, $name) {
                $features = $m->getItemData($name, []);
                return array_map(function ($feature) {
                    if (! property_exists($feature, 'name_cn')) $feature->name_cn = null;
                    $feature->name = \WS::langText($feature->name, $feature->name_cn);

                    if (! property_exists($feature, 'description_cn')) $feature->description_cn = null;
                    $feature->description = \WS::langText($feature->description, $feature->description_cn);
                    return $feature;
                }, $features);
            },
            'k12' => function ($m, $name) {
                $schools = $m->schools;
                return [
                    'high' => count($schools->high),
                    'middle' => count($schools->middle),
                    'grade' => count($schools->grade)
                ];
            }
        ];
    }
}