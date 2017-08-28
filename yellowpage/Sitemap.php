<?php
namespace common\yellowpage;

use yii\db\Query;
use common\helper\DbQuery;

class Sitemap
{
    public static function map()
    {
        $query = (new \yii\db\Query())
            ->select('id')
            ->from('catalog_yellow_page')
            ->orderBy(['id' => 'DESC']);

        return $query->all();
    }
}