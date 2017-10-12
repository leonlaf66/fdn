<?php
namespace models;

class SiteChartSetting extends ActiveRecord
{
    const AVG_PRICE_PROPS = ['MF', 'SF', 'CC'];
    const RENTAL_PROP = 'RN';
    const ENABLED_PROPS = ['ACT','NEW','BOM','PCG','RAC','EXT'];
    
    public static function tableName()
    {
        return 'site_chart_setting';
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    public static function findData ($path)
    {
        if (is_array($path)) {
            $rows = self::find()->where(['in', 'path', $path])->all();
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

    public static function updateData ($path, $data)
    {
        $entity = self::find()
            ->where([
                'path'=>$path
            ])
            ->one();

        if (! $entity) {
            $entity = new self();
            $entity->path = $path;
        }
        $entity->data = json_encode($data);

        return $entity->save();
    }
}