<?php
namespace common\listhub\estate;

use WS;

class Report
{
	public static function totals($stateId)
	{
		$sql = "select prop_type,count(*) as total 
					from listhub_index
					where state='{$stateId}'
					  and is_show=true
					  and list_price > 0
					group by prop_type";

		$rows = WS::$app->db->createCommand($sql)->queryAll();

		$results = [0=>0, 1=>0];
		foreach($rows as $row) {
		    if ($row['prop_type'] === 'RN') {
                $results[1] = $row['total'];
            } else {
                $results[0] += $row['total'];
            }
		}
		return $results;
	}
}