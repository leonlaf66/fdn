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

        if (\WS::$app->language === 'zh-CN' && isset($options['zh-CN'])) {
            $options = \yii\helpers\ArrayHelper::merge($options, (array)$options['zh-CN']);
            unset($options['zh-CN']);
        }

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
            'rawValue'=>$rawValue,
            'prefix' => null,
            'value'=>$value,
            'suffix' => null,
            'formatedValue' => null,
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
                $this->_customFormat($formater, $result);
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

        if(is_null($result['value']) || $result['value'] === '') {
            $result['value'] =  tt('Unknown', '未提供');
            $result['prefix'] = null;
            $result['suffix'] = null;
        }

        // 清除多余无用的前后缀
        if(!$result['prefix']) unset($result['prefix']);
        if(!$result['suffix']) unset($result['suffix']);

        //格式化过后的数据
        $result['formatedValue'] = $result['value'];
        if (isset($result['prefix'])) {
            $result['formatedValue'] = $result['prefix'].$result['formatedValue'];
        }
        if (isset($result['suffix'])) {
            $result['formatedValue'] = $result['formatedValue'].$result['suffix'];
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

        $xml = simplexml_load_string("<groups>{$xmlContent}</groups>");
        $groups = $xml->xpath('/groups/group');
        foreach($groups as $group) {
            $arrGroup = ['title'=>(string)$group->title,'items'=>[]];
            if(isset($group->layout)) $arrGroup['layout'] = (string)($group->layout);
            $items = $group->xpath('items');
            foreach($items[0] as $item) {
                $name = $item->getName();
                $options = (array)$item;

                if(isset($options['values'])) {
                    $options['values'] = (array)$options['values'];
                }
                /*
                if (\WS::$app->language === 'zh-CN' && isset($options['zh-CN'])) {
                    $options = \yii\helpers\ArrayHelper::merge($options, (array)$options['zh-CN']);
                }*/

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

    protected function _customFormat($type, & $result)
    {
        $val = $result['value'];
        $resultNew = [];

        switch ($type) {
            case 'price':
            case 'sell.total.price':
                if (\WS::$app->language === 'zh-CN') {
                    if (floatval($val) > 10000) {
                        $resultNew['value'] = number_format(floatval($val) / 10000.0);
                        $resultNew['prefix'] = null;
                        $resultNew['suffix'] = '万美元';
                    } else {
                        $resultNew['prefix'] = null;
                        $resultNew['suffix'] = '美元';
                        $resultNew['value'] = number_format(floatval($val), 0);
                    }
                } else {
                    $resultNew['prefix'] = '$';
                    $resultNew['suffix'] = null;
                    $resultNew['value'] = number_format(floatval($val), 0);
                }
                break;
            case 'area':
                if (\WS::$app->language === 'zh-CN') {
                    $resultNew['value'] = number_format(intval(floatval($val) * 0.092903), 0);
                    $resultNew['prefix'] = null;
                    $resultNew['suffix'] = '平方米';
                } else {
                    $resultNew['value'] = number_format(floatval($val), 0);
                    $resultNew['suffix'] = 'Sq.Ft';
                }
                break;
            case 'price.per.sq-ft':
                if (\WS::$app->language === 'zh-CN') {
                    $resultNew['value'] = number_format(floatval($val) / 0.092903, 0);
                    $resultNew['suffix'] = '美元/平方米';
                } else {
                    $resultNew['value'] = number_format(floatval($val), 0);
                    $resultNew['suffix'] = null;
                }

        }

        $result = array_merge($result, $resultNew);
    }

    protected function _getConfigFile($name)
    {
        return dirname(__DIR__).'/config/rets.fields.map/'.$name.'.php';
    }
}