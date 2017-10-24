<?php
namespace common\component;

use yii\helpers\ArrayHelper;

class Area extends \yii\base\Component
{
    public $maps = [];
    protected $_data = [];

    public function initArea($id)
    {
        if (isset($this->maps[$id])) {
            $this->_data = [
                'id' => $id,
                'settings' => $this->maps[$id]
            ];
        }
    }

    public function getId()
    {
        return ArrayHelper::getValue($this->_data, 'id');
    }

    public function getStateIds()
    {
        $settings = ArrayHelper::getValue($this->_data, 'settings');
        return ArrayHelper::getValue($settings, 'stateIds');
    }
}