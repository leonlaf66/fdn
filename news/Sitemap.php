<?php
namespace common\news;

use yii\db\Query;
use common\helper\DbQuery;

class Sitemap
{
    public static function map()
    {
        $query = (new \yii\db\Query())
            ->select('id, updated_at')
            ->from('news')
            ->where('status=1')
            ->orderBy(['updated_at' => 'DESC']);

        return $query->all();
    }
}