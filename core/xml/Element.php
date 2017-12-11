<?php
namespace common\core\xml;

class Element extends \SimpleXMLElement
{
    public function one($path)
    {
        $element = parent::xpath($path);
        if (empty($element)) return new self('<empty></empty>');
        return isset($element[0]) ? $element[0] : $element;
    }

    public function __get($name)
    {
        var_dump($name);exit;
        parent::__get($name);
    }

    public function val($defValue = null)
    {
        $value = $this->__toString();
        return $value === '' ? $defValue : $value;
    }
}