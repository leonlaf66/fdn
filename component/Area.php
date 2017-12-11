<?php
namespace common\component;

use yii\helpers\ArrayHelper;

class Area extends \yii\base\Component
{
    public $sites = [];
    public $maps = [];
    protected $_data = [];

    public function initArea($id)
    {
        if (isset($this->maps[$id])) {
            $this->_data = array_merge(['id' => $id], $this->maps[$id]);
        }
    }

    public function getId()
    {
        return ArrayHelper::getValue($this->_data, 'id', 'ma');
    }

    public function getStateIds()
    {
        return ArrayHelper::getValue($this->_data, 'stateIds', []);
    }

    public function getStateId()
    {
        return $this->getStateIds()[0];
    }

    public function getIsAreaSite()
    {
        $parts = explode('.', $_SERVER["HTTP_HOST"]);
        $areaId = $parts[0];
        return in_array(strtolower($areaId), ['ma', 'ny', 'ca', 'ga', 'il']) && \WS::$app->area->id;
    }

    public function getName()
    {
        return ArrayHelper::getValue($this->_data, 'name', '');
    }
}