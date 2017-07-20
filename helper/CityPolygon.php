<?php
namespace common\helper;

class CityPolygon
{
    public static function get($cityName)
    {
        $cityName = strtolower($cityName);
        if(strpos($cityName, ' ') !== false) {
            $cityName = str_replace(' ', '-', $cityName);
        }

        $polygonFile = \common\estate\helpers\Rets::getConfigFile('map.city.polygon/'.$cityName.'.php');
        if(file_exists($polygonFile)) {
            return include($polygonFile);
        }
        
        return [];
    }
}
