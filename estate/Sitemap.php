<?php
namespace common\estate;

use yii\db\Query;
use common\helper\DbQuery;

class Sitemap
{
    public static function map($areaId, $callable, $limit = 4000)
    {
        $query = (new \yii\db\Query())
            ->select('list_no, prop_type, index_at')
            ->from('house_index_v2')
            ->where(['area_id' => $areaId])
            ->andWhere('is_online_abled=true')
            ->orderBy(['index_at' => 'DESC'])
            ->limit($limit);

        $grountIndex = 0;
        DbQuery::patch($query, $limit, function ($query, $totalCount, $that) use ($callable, & $grountIndex) {
            $rows = $query->all();
            $callable($rows, $grountIndex);
            $grountIndex ++;
        });
    }
}