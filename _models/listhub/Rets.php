<?php
namespace models\listhub;

use WS;

class Rets extends \models\ActiveRecord
{
    public static function tableName()
    {
        return 'mls_rets_listhub';
    }

    public static function primaryKey()
    {
        return ['list_no'];
    }

    public static function getDb()
    {
        return WS::$app->mlsdb;
    }

    public function getXmlElement()
    {
        return static::toModel($this->xml);
    }

    public static function toModel($xml)
    {
        $clearTags = [' xmlns="http://rets.org/xsd/Syndication/2012-03" xmlns:commons="http://rets.org/xsd/RETSCommons"', 'commons:'];
        foreach ($clearTags as $clearTag) {
            if (false !== strpos($xml, $clearTag)) {
                $xml = str_replace($clearTag, '', $xml);
            }
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.$xml;

        return @ simplexml_load_string($xml, '\common\core\xml\Element');
    }
}
