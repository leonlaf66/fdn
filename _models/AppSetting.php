<?php
namespace models;

class AppSetting extends ActiveRecord
{
    public static function tableName()
    {
        return 'app_setting';
    }

    public static function primaryKey()
    {
        return ['app_id', 'config_id'];
    }

    public static function submit($appId, $configId, $configContent)
    {
        $config = self::get($appId, $configId);
        if (!$config) {
            $config = new self();
            $config->app_id = $appId;
            $config->config_id = $configId;
        }
        $config->config_content = $configContent;
        return $config->save();
    }

    public static function get($appId, $configId)
    {
        return self::find()
            ->where(['app_id' => $appId, 'config_id' => $configId])
            ->one();
    }
}