<?php
namespace common\estate;

use yii\db\Query;
use common\helper\DbQuery;

class Sitemap
{
    public static function map($areaId, $callable, $limit = 4000, $params = [])
    {
        $query = (new \yii\db\Query())
            ->select('list_no, prop_type, index_at')
            ->from('house_index_v2')
            ->where(['area_id' => $areaId])
            ->andWhere('is_online_abled=true')
            ->andWhere('prop_type is not null')
            ->andWhere('city_id is not null')
            ->orderBy(['index_at' => 'DESC'])
            ->limit($limit);

        DbQuery::patch($query, $limit, function ($query, $opts) use ($callable) {
            $rows = $query->all();
            $callable($rows, $opts);
        }, $params);
    }
}