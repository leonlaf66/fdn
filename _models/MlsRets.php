<?php
namespace models;

use WS;

class MlsRets extends ActiveRecord
{
    public $json = [];

    public static function tableName()
    {
        return 'mls_rets';
    }

    public static function primaryKey()
    {
        return ['list_no'];
    }

    public static function getDb()
    {
        return WS::$app->mlsdb;
    }

    public function rules()
    {
        return [
            [['json'], 'safe']
        ];
    }

    public function __get($name)
    {
        $value = null;
        try {
            $value = parent::__get($name);
        } catch (\Exception $e) {
            return $this->getJsonData($name);
        }
        return $value;
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->json = json_decode($this->json_data);
        unset($this->json_data);
    }

    public function getJsonData($name, $def = null)
    {
        return isset($this->json->$name) ? $this->json->$name : $def;
    }
}