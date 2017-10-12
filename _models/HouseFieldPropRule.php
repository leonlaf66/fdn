<?php
namespace models;

use WS;

class HouseFieldPropRule extends ActiveRecord
{
    public static function tableName()
    {
        return 'house_field_prop_rule';
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