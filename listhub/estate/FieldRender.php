<?php
namespace common\listhub\estate;

class FieldRender
{
    public static function filter($elements, $filter)
    {
        $value = null;
        switch ($filter) {
            case 'count':
                $value = count($elements);
                break;
            case 'values':
                $values = [];
                foreach ($elements as $_element) {
                    $value = $_element->val();
                    if (\WS::$app->language === 'zh-CN') {
                        $nodeName = $_element->getName();
                        $langs = \common\listhub\estate\References::getLangs($nodeName);
                        if (isset($langs[$value]) && $langs[$value] !== '') {
                            $value = $langs[$value];
                        }
                    }
                    $values[] = $value;
                }
                $value = $values;
        }
        return $value;
    }

    public static function format(& $data, $type, $m)
    {
        if (is_object($type) && get_class($type) === 'Closure') {
            $data['value'] = $type($data['value'], $m);
            return;
        }

        switch ($type) {
            case 'money':
                if (intval($data['value']) < 10000) {
                    $data = array_merge($data, [
                        'value' => number_format($data['value'], 0),
                        'prefix' => '$'
                    ]);
                    break;
                }
                $data = array_merge($data, [
                    'value' => tt(number_format($data['value'], 0), number_format($data['value'] / 10000, 2)),
                    'prefix' => tt('$', ''),
                    'suffix' => tt('', '万美元')
                ]);
                break;
            case 'sq.ft':
                if (\WS::$app->language === 'zh-CN') {
                    $data = array_merge($data, [
                        'value' => number_format(intval(floatval($data['value']) * 0.092903), 0),
                        'suffix' => '平方米'
                    ]);
                } else {
                    $data = array_merge($data, [
                        'value' => number_format(floatval($data['value']), 0),
                        'suffix' => 'Sq.Ft'
                    ]);
                }
                break;
            case 'money/sq.ft':
                if (\WS::$app->language === 'zh-CN') {
                    $data['value'] = number_format(floatval($data['value']) / 0.092903, 0);
                    $data['suffix'] = '美元/平方米';
                } else {
                    $data['value'] = number_format(floatval($data['value']), 0);
                }
                break;
            case 'yes/no':
                $data['value'] = $data['value'] === 'true' ? tt('Yes', '是') : tt('No', '否');
                return;
            case 'have/not':
                $data['value'] = $data['value'] === 'true' ? tt('Yes', '有') : tt('No', '无');
                break;
        }
    }
}