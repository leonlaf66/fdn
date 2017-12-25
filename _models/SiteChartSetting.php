<?php
namespace models;

class SiteChartSetting extends ActiveRecord
{
    public static function tableName()
    {
        return 'site_chart_setting';
    }

    public static function primaryKey()
    {
        return ['path', 'area_id'];
    }

    public static function findData ($areaId, $path)
    {
        if (is_array($path)) {
            $rows = self::find()
                ->where(['area_id' => $areaId])
                ->andWhere(['in', 'path', $path])
                ->all();
            return array_key_value($rows, function ($d) {
                return [
                    $d->path,
                    json_decode($d->data)
                ];
            });
        }

        if ($entity = self::find()->where(['path' => $path])->one()) {
            return json_decode($entity->data);
        }
        
        return null;
    }
}