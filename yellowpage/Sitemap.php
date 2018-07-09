<?php
namespace common\yellowpage;

use yii\db\Query;
use common\helper\DbQuery;

class Sitemap
{
    public static function map($areaId)
    {
        $query = (new \yii\db\Query())
            ->select('id')
            ->from('yellow_page')
            ->where('area_id', strtolower($areaId))
            ->orderBy(['id' => 'DESC']);

        return $query->all();
    }
}