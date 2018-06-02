<?php
namespace common\helper;

class DbQuery
{
    public static function patch($query, $size, $calback, $params = null, & $db = null)
    {
        $count = $query->count('*', $db);
        
        $groups = round($count * 1.0 / $size);

        if ($groups < 1) {
            $groups = $count > 0 ? 1: 0;
        }

        for ($group = 1; $group <= $groups; $group ++) {
            $theQuery = clone $query;

            $theQuery->offset(($group - 1) * $size);
            $calback($theQuery, ['total' => $count, 'params'=>$params, 'groupCount' => $groups, 'groupIdx' => $group]);

            unset($theQuery);
        }
    }
}