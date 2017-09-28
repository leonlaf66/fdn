<?php
namespace common\cms\models;

class SiteSeoMeta extends \common\core\ActiveRecord
{
    public $arrayFields = ['title', 'keywords', 'description'];

    public static function tableName()
    {
      return 'site_seo_meta';
    }

    public static function findOneAsArray($path)
    {
        $m = parent::findOne($path);
        if (! $m) {
            return [
                'title' => ['', ''],
                'keywords' => ['', ''],
                'description' => ['', '']
            ];
        }

        return [
            'title' => $m->title,
            'keywords' => $m->keywords,
            'description' => $m->description
        ];
    }
}