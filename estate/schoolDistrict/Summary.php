<?php
namespace common\estate\schoolDistrict;

use WS;
use \common\catalog\SchoolDistrict;

class Summary extends \common\core\ActiveRecord
{
    public $json = [];

    public static function tableName()
    {
        return 'rets_area_summaries';
    }

    public static function primaryKey()
    {
        return ['town'];
    }

    public static function findSummary($code)
    {
        static $summaryItems = [];

        if (empty($summaryItems)) {
            $list = self::find()->all();
            foreach ($list as $d) {
                $town = $d['town'];
                $summaryItems[$town] = $d;
            }
        }

        return $summaryItems[$code];
    }

    public static function flush($callback)
    {
        $commands = [
            'total' => 'flushTotal',
            'avg_price' => 'flushAvgPrice',
            'avg_monthly_rent_price' => 'flushAvgMonthlyRentPrice',
            'trading_volume_year' => 'flushTradingVolumeYear'
        ]; 

        WS::$app->db->createCommand('delete from rets_area_summaries')->execute();

        $sdRows = SchoolDistrict::xFind()->all();
        $count = count($sdRows);
        foreach ($sdRows as $index => $sdRow) {
            $id = $sdRow['id'];
            $codes = explode('/', $sdRow['code']);

            $data = ['town' => $sdRow['code']];
            foreach ($commands as $fieldId => $command) {
                $data[$fieldId] = static::$command($codes);
            }

            WS::$app->db->createCommand()->insert('rets_area_summaries', $data)->execute();

            $callback($sdRow, $index + 1, $count);
        }

        echo "\n";
    }

    public static function flushTotal($towns)
    {
        $townTotals = self::getAllTownSallTotals();

        $total = 0;
        foreach ($towns as $town) {
            if (isset($townTotals[$town])) {
                list($count, $totalPrice) = $townTotals[$town]['sell'];
                $total += $count;
            }
        }
        return $total;
    }

    public static function flushAvgPrice($towns)
    {
        $townTotals = self::getAllTownSallTotals(true);

        $resultTotal = 0;
        $resultCount = 0;
        foreach ($towns as $town) {
            if (isset($townTotals[$town])) {
                list($count, $totalPrice) = $townTotals[$town]['sell'];
                $resultTotal += $totalPrice;
                $resultCount += $count;
            }
        }

        return $resultCount > 0 ? $resultTotal / $resultCount : 0;
    }

    public static function flushAvgMonthlyRentPrice($towns)
    {
        $townTotals = self::getAllTownSallTotals();

        $resultTotal = 0;
        $resultCount = 0;
        foreach ($towns as $town) {
            if (isset($townTotals[$town]) && isset($townTotals[$town]['rent'])) {
                list($count, $totalPrice) = $townTotals[$town]['rent'];
                $resultTotal += $totalPrice;
                $resultCount += $count;
            }
        }
        return $resultCount > 0 ? $resultTotal / $resultCount : 0;
    }

    public static function flushTradingVolumeYear($towns)
    {
        static $totals = [];

        if (empty($totals)) {
            $sql = "
                select town, count(id) as count
                  from rets_mls_index 
                  where prop_type <> 'RN'
                     and list_date > now() + '-1 year'
                     and is_show = false
                  group by town";

            $townTotalRows = \WS::$app->db->createCommand($sql)->queryAll();
            foreach ($townTotalRows as $row) {
                $code = $row['town'];
                $totals[$code] = $row['count'];
            }
        }
        
        $resultTotal = 0;
        foreach ($towns as $town) {
            $resultTotal += $totals[$town] ?? 0;
        }

        return $resultTotal;
    }

    // 获取分town的房源数量以及总价(分售房/租房)
    public static function getAllTownSallTotals($listMode = false)
    {
        static $townTotals = [];


        if (empty($townTotals)) {
            // 获取totals数据
            $addiWhere = $listMode ? "and prop_type in ('SF', 'MF', 'CC')": '';
            $sql = "
                select town, 
                    (case when is_rental then 'rent' else 'sell' end) as type, count(id) as count,
                     sum(list_price) as total_price  
                  from rets_mls_index 
                  where is_show = true
                  {$addiWhere}
                  group by town,
                     (case when is_rental then 'rent' else 'sell' end)";

            $townTotalRows = \WS::$app->db->createCommand($sql)->queryAll();

            // 映射totals
            foreach ($townTotalRows as $row) {
                $town = $row['town'];

                if (! isset($townTotals[$town])) {
                    $townTotals[$town] = [];
                }

                $property = $row['type'];

                $townTotals[$town][$property] = [$row['count'], $row['total_price']];
            }
        }

        return $townTotals;
    }
}