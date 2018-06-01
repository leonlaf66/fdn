<?php
namespace models;

class SiteSeoMeta extends ActiveRecord
{
    public $arrayFields = ['title'];

    public static function tableName()
    {
        return 'site_seo_meta_cn';
    }

    public static function findOneAsArray($area, $path)
    {
        $m = parent::find()->where(['area_id' => $area, 'path' => $path])->one();
        if (! $m) {
            return [
                'title' => ['', ''],
                'keywords' => '',
                'description' => ''
            ];
        }

        return [
            'title' => $m->title,
            'keywords' => $m->keywords,
            'description' => $m->description
        ];
    }
}