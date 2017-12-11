<?php
namespace models\listhub;

use WS;

class HouseIndex extends \models\ActiveRecord
{
    protected $xmlElement = null;

    public static function tableName()
    {
        return 'listhub_index';
    }

    public function getRets()
    {
        return $this->hasOne(Rets::className(), ['list_no'=>'id']);
    }

    public function getXmlElement()
    {
        if (is_null($this->xmlElement)) {
            $this->xmlElement = $this->rets->getXmlElement();
        }
        return $this->xmlElement;
    }
}