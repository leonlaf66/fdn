<?php
namespace common\estate;

use WS;

class Report
{
	public static function totals()
	{
		$sql = "select is_rental,count(*) as total 
					from house_index
					where is_show=true
					group by is_rental";

		$rows = WS::$app->db->createCommand($sql)->queryAll();

		$results = [0=>0, 1=>0];
		foreach($rows as $row) {
			$results[$row['is_rental']] = $row['total'];
		}
		return $results;
	}
}