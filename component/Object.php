<?php
namespace common\component;

class Object implements \ArrayAccess
{
    private $_data = [];

    public function __construct($data)
    {
        $this->_data = $data;
    }

    public function __set($name, $val)
    {
        $this->_data[$name] = $val;
    }

    public function __get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->_data[] = $value;
        } else {
            $this->_data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->_data[$offset]) ? $this->_data[$offset] : null;
    }

    public function set($name, $value)
    {
        $this->$name = $value;
    }

    public function get($name, $def = null)
    {
        return $this->$name ? $this->$name : $def;
    }

    public function data()
    {
        return $this->_data;
    }
}