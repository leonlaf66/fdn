<?php
namespace common\estate;

use yii\db\Query;
use common\helper\DbQuery;

class Sitemap
{
    public static function map($callable, $limit = 4000)
    {
        $query = (new \yii\db\Query())
            ->select('id, is_rental, index_at')
            ->from('house_index')
            ->where('is_show=true')
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