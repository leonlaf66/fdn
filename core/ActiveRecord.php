<?php
namespace common\core;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public static $_underscoreCache = [];

    public $arrayFields = [];
    public $jsonFields = [];

    public function afterFind()
    {
        $result = parent::afterFind();
        $this->processFieldData('load');
        return $result;
    }

    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);
        $this->processFieldData('save');
        return $result;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $result = parent::afterSave($insert, $changedAttributes);
        $this->processFieldData('load');
        return $result;
    }

    protected function processFieldData($type = 'load' /*save*/)
    {
        if ($type === 'load') {
            foreach($this->arrayFields as $arrayField) {
                if ($arrayFiledValue = $this->$arrayField) {
                    $arrayFiledValue = substr($arrayFiledValue, 1);
                    $arrayFiledValue = substr($arrayFiledValue, 0, strlen($arrayFiledValue) - 1);

                    $this->$arrayField = explode(',', $arrayFiledValue);
                } else {
                    $this->$arrayField = [];
                }
            }

            foreach($this->jsonFields as $jsonField) {
                if ($this->$jsonField) {
                    $this->$jsonField = json_dncode($this->$jsonField);
                }
            }
        } elseif ($type === 'save') {
            foreach($this->arrayFields as $arrayField) {
                if ($this->$arrayField) {
                    $this->$arrayField = '{'.implode(',', $this->$arrayField).'}';
                } else {
                    $this->$arrayField = '{}';
                }
            }

            foreach($this->jsonFields as $jsonField) {
                if ($this->$jsonField) {
                    $this->$jsonField = json_encode($this->$jsonField);
                }
            }
        }
    }
    
    public function setData($key, $value)
    {
        return parent::__set($key, $value);
    }

    public function getData($key, $defValue=null)
    {
        $value = parent::__get($key);
        return $value ? $value : $defValue;
    }

    public function __call($method, $args) {
        switch (substr($method, 0, 3)) {
            case 'get' :
                $key = $this->_underscore(substr($method,3));
                $data = $this->getData($key, isset($args[0]) ? $args[0] : null);
                return $data;

            case 'set' :
                $key = $this->_underscore(substr($method,3));
                $result = $this->setData($key, isset($args[0]) ? $args[0] : null);
                return $result;

            case 'uns' :
                $key = $this->_underscore(substr($method,3));
                $result = parent::__unset($key);
                return $result;

            case 'has' :
                $key = $this->_underscore(substr($method,3));
                return parent::__isset($key);
        }
        throw new \Exception("Invalid method ".get_class($this)."::".$method."(".print_r($args,1).")");
    }

    public function __set($var, $value)
    {
        $var = $this->_underscore($var);
        $this->setData($var, $value);
    }

    public function __get($var)
    {
        $var = $this->_underscore($var);
        return $this->getData($var);
    }

    protected function _underscore($name)
    {
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }
        $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
        self::$_underscoreCache[$name] = $result;
        return $result;
    }
}