<?php
namespace common\estate\helpers;

use WS;
use yii\helpers\ArrayHelper as Ah;

class RetsRender
{
    public $entity = null;
    public $map = [];

    public function __construct($entity)
    {
        $this->entity = $entity;
        $this->map = $this->_loadMap();
    }

    public function get($field, $options=[])
    {
        $options = array_merge($this->_fieldMap($field), $options);

        //数据字段名
        $index = Ah::getValue($options, 'index', $field);

        //数据
        $value = $rawValue = $index ? $this->entity->$index : null;

        //数据类型
        if ($typeName = Ah::getValue($options, 'type')) {
            $typer = $typeName.'val';
            $value = $typer($value);
        }

        //默认数据填充
        if (isset($options['default']) && empty($value)) {
            $value = $options['default'];
        }

        //初始
        $result = [
            'id'=>$field,
            'title'=>'',
            'value'=>$value,
            'rawValue'=>$rawValue,
        ];
        
        //标题
        $result['title'] = Ah::getValue($options, 'title', '');

        //值数据
        if ($valueCaller = Ah::getValue($options, 'value')) {
            $result['value'] = $valueCaller($this->entity->$index, $this->entity);
        }

        //显示数据
        if ($valueAlias = Ah::getValue($options, 'values')) {
            if ($theFieldValueAlias = Ah::getValue($valueAlias, $result['value'])) {
                $result['value'] = $theFieldValueAlias;
            }
        } elseif ($relFields = Ah::getValue($options, 'map')) {
            $result['value'] = \common\estate\dict\Reference::get(strtolower($this->entity->prop_type), $index, $value);
        }

        //数据格式化
        if ($formater = Ah::getValue($options, 'format')) {
            if (is_string($formater)) {
                $result['value'] = $this->_customFormat($formater, $result['value']);
            } else {
                $result['value'] = $formater($result['value']);
            }
        }

        //前缀
        if ($prefix = Ah::getValue($options, 'prefix')) {
            $result['prefix'] = $prefix;    
        }
        
        //后缀
        if ($suffix = Ah::getValue($options, 'suffix')) {
            $result['suffix'] = $suffix;
        }

        return $result;
    }

    public function detail()
    {
        $typeId = strtolower($this->entity->prop_type);
        $xmlContent = (new \yii\db\Query)->select('xml_rules')
            ->select('xml_rules')
            ->from('rets_detail_field_rules')
            ->where(['type_id'=>$typeId])->scalar();

        $arrGroups = [];
        $t = lang('rets');

        $xml = simplexml_load_string("<groups>{$xmlContent}</groups>");
        $groups = $xml->xpath('/groups/group');
        foreach($groups as $group) {
            $arrGroup = ['title'=>$t((string)$group->title,[], true),'items'=>[]];
            if(isset($group->layout)) $arrGroup['layout'] = (string)($group->layout);
            $items = $group->xpath('items');
            foreach($items[0] as $item) {
                $name = $item->getName();
                $options = (array)$item;

                if(isset($options['values'])) {
                    $options['values'] = (array)$options['values'];
                }
                if(isset($options['zh-CN'])) {
                    $options['zh-CN'] = (array)$options['zh-CN'];
                }

                $arrGroup['items'][$name] = $this->get($name, $options);
            }
            $arrGroups[] = $arrGroup;
        }
        
        return $arrGroups;
    }

    protected function _fieldMap($field, $options = [])
    {
        return Ah::merge(
            isset($this->map[$field]) ? $this->map[$field] : [],
            $options
        );
    }

    protected function _loadMap()
    {
        $map = Ah::merge(
            include($this->_getConfigFile('base')),
            include($this->_getConfigFile(strtolower($this->entity->prop_type)))
        );

        if (WS::$app->language == 'zh-CN') {
            $propertyType = $this->entity->prop_type === 'RN' ? 'zh-CN_renal' : 'zh-CN_sell';

            $map = Ah::merge(
                $map,
                include($this->_getConfigFile($propertyType))
            );
        }

        return $map;
    }

    protected function _customFormat($type, $val)
    {
        switch ($type) {
            case 'sell.total.price':
                return number_format(floatval($val) * 1.0 / 10000, 2);
            case 'area':
                return intval(floatval($val) * 0.092903);
            case 'price.per.sq-ft':
                return number_format(floatval($val) / 0.092903, 2);
        }
        return $val;
    }

    protected function _getConfigFile($name)
    {
        return dirname(__DIR__).'/config/rets.fields.map/'.$name.'.php';
    }
}