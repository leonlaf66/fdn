<?php
namespace common\estate\helpers;

use common\estate\helpers\Rets as RetsHelpers;

class DetailFieldRules 
{
    private $_type;
    private $_file;
    private $_rawContent;

    public function __construct($type)
    {
        $this->_type = $type;
        $this->_file = RetsHelpers::getConfigFile("details/rets.view.{$type}.map.php");
        $this->_rawContent = file_get_contents($this->_file);
    }

    public static function findOne($type)
    {
        return new self($type);
    }

    public function getFile()
    {
        return $this->_file;
    }

    public function getRawContent()
    {
        return $this->_rawContent;
    }
}