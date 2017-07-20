<?php
namespace common\estate\dict;

use WS;

class MapDetail extends \yii\base\Model
{
    public $type;
    public $content;

    public static function findOne($type)
    {
        $m = new static();
        $m->type = $type;

        $cacheFile = self::getCacheFile($type);
        if(file_exists($cacheFile)) {
            $m->content = file_get_contents($cacheFile);
        }

        return $m;
    }

    public static function getCacheFile($type)
    {
        return __DIR__.'/../config/details/rets.view.'.$type.'.map.php';
    }

    public function asArray()
    {
        $arrGroups = [];

        $xml = simplexml_load_string("<groups>{$this->content}</groups>");
        $groups = $xml->xpath('/groups/group');
        foreach($groups as $group) {
            $arrGroup = ['title'=>(string)$group->title,'items'=>[]];
            if(isset($group->layout)) $arrGroup['layout'] = (string)($group->layout);
            $items = $group->xpath('items');
            foreach($items[0] as $item) {
                $arrGroup['items'][$item->getName()] = new \module\estate\models\dict\FieldMap((array)$item);
            }
            $arrGroups[] = $arrGroup;
        }

        return $arrGroups;
    }
}

class ViewTemplateItem extends \yii\base\Object
{
    public $title;
    public $suffix;
    public $prefix;
    public $values;
}