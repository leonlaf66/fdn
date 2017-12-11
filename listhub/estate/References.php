<?php
namespace common\listhub\estate;

class References
{
    protected static $data = null;

    public static function findPropTypeCode($propTypeName, $propSubTypeName)
    {
        $maps = Setting::get('references','propTypesMap', []);
        foreach ($maps as $code => $conditionCallable) {
            if ($conditionCallable($propTypeName, $propSubTypeName)) {
                return $code;
            }
        }

        return false;
    }

    public static function findCodeByName($type, $name, $defValue = null)
    {
        $names = Setting::get('references', $type, []);
        $findedKey = array_search($name, $names);
        return $findedKey ? $findedKey : $defValue;
    }

    public static function getPropTypeNames()
    {
        return Setting::get('references', 'propTypes', []);
    }

    public static function getLangs($code)
    {
        static $cache = [];
        if (! isset($cache[$code])) {
            $langFile = __DIR__."/etc/langs/values/{$code}.php";
            if (file_exists($langFile)) {
                $cache[$code] = include $langFile;
            } else {
                $cache[$code] = [];
            }
        }
        return $cache[$code];
    }
}
