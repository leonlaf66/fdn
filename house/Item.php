<?php
namespace common\house;

use function foo\func;

class Item extends \models\HouseItem
{
    public function getFieldValue($name, $opts = [])
    {
        $opts = array_merge(
            self::getFieldMaps($name),
            $opts
        );

        if (! empty($opts)) {
            $value = null;
            if (isset($opts['render'])) {
                $value = ($opts['render'])($this, $opts);
            } elseif ($this->state !== 'MA' && isset($opts['path'])) {
                $value = $this->getXml()->xpath($opts['path']);
            }

            if (isset($opts['filter'])) {
                if (get_class($opts['filter']) === 'Closure') {
                    $value = ($opts['filter'])($data['value']);
                } else {
                    $value = FieldRender::filter($data['value'], $opts['filter']);
                }
            }

            return $value;
        }

        return $this->__get($name);
    }

    public function renderFieldData($name, $opts = [])
    {
        $opts = array_merge(
            self::getFieldMaps($name),
            $opts
        );

        $data = [
            'title' => $opts['title'] ?? '',
            'value' => $this->getFieldValue($name, $opts)
        ];

        // format
        $data['formatedValue']  = $data['value'];
        if (isset($opts['format'])) {
            if (get_class($opts['format']) === 'Closure') {
                $data['formatedValue'] = ($opts['format'])($data['value'], $opts);
            } else {
                FieldRender::format($data['value'], $opts['format'], $this);
            }

        }

        // 其它选项渲染
        foreach (['prefix', 'suffix'] as $optId) {
            if (isset($opts[$optId])) {
                if (is_object($opts[$optId]) && get_class($opts[$optId]) === 'Closure') {
                    $data[$optId] = ($opts[$optId])($this);
                } else {
                    $data[$optId] = $opts[$optId];
                }
            }
        }

        return $data;

    }

    public function details()
    {
        return [];
    }

    public static function getFieldMaps($name = null)
    {
        static $maps = [];
        if (empty($maps)) {
            $maps = include(__DIR__.'/etc/fields/'.($this->state === 'MA' ? 'mls' : 'listhub').'.fields.php');
        }

        if (! is_null($name)) {
            $opts = $maps[$name] ?? [];
            if (\WS::$app->language === 'zh-CN' && isset($opts['zh-CN'])) {
                $opts = array_merge($opts, $opts['zh-CN']);
                unset($opts['zh-CN']);
            }
            return $opts;
        }

        return $maps;
    }
}

class FieldRender
{
    public static function filter($elements, $filter, $m)
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
                        'prefix' => tt('$', ''),
                        'suffix' => tt('', '美元')
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