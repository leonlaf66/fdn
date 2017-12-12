<?php
namespace models;

class SiteSetting extends ActiveRecord
{
    protected static $_data = [];
    
    public static function tableName()
    {
        return 'site_setting';
    }

    public static function getValue($path, $areaId = null)
    {
        $options = \WS::$app->configuationData[$path] ?? [];

        if(empty(self::$_data)) {
            self::$_data = self::_loadAllData($areaId);
        }
        return isset(self::$_data[$path]) ?
            json_decode(self::$_data[$path])
            :
            $options['default'] ?? null;
    }

    public static function getJson($path, $areaId = null)
    {
        $options = \WS::$app->configuationData[$path];

        if(empty(self::$_data)) {
            self::$_data = self::_loadAllData($areaId);
        }
        return isset(self::$_data[$path]) ?
            json_decode(self::$_data[$path])
            :
            $options['default'] ?? null;
    }

    public static function get($path, $areaId = null)
    {
        return self::getValue($path, $areaId);
    }

    protected static function _loadAllData($areaId = null)
    {
        $data = [];

        $items = self::find()->where('site_id=:area_id or site_id is null', [':area_id' => $areaId])->all();
        foreach($items as $item) {
            $data[$item->path] = $item->value;
        }
        return $data;
    }
}