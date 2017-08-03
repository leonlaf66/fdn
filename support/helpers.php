<?php
function helper($name, $score = 'yii')
{
    if ($score === 'yii') {
        $name = ucfirst($name);
        return '\\yii\\helpers\\'.$name.'Helper';
    }
}

function t($type, $text, $params=[]) {
    return \WS::t($type, $text, $params);
}

function _t($type, $text, $params=[])
{
    echo t($type, $text, $params);
}

function lang($type, $return = false)
{
    return \WS::lang($type, $return);
}

function tt()
{
    $texts = func_get_args();
    if (count($texts) === '') return '';

    if (\Yii::$app->language === 'en-US') 
        return $texts[0];
    else 
        return count($texts) > 1 ? $texts[1] : $texts[1];
}

function _tt()
{
    $texts = func_get_args();
    echo call_user_func_array('tt', $texts);
}

function additionMonthDate($monthNum = -1)  
{  
    list($year, $month, $day)= explode('-', '2017-03-31');
    // list($year, $month, $day)= explode('-', date('Y-m-d'));

    $newMonth=mktime(0, 0, 0, $month + $monthNum, $day, $year);
    return date('Y-m-d', $newMonth);
}

function array_key_value($array, $callable)
{
    $results = [];
    foreach ($array as $key => $row) {
        list($key, $value) = $callable($row, $key);
        $results[$key] = $value;
    }
    return $results;
}

function get_fdn_etc()
{
    return include(dirname(__DIR__).'/etc/main.php');
}

function media_url($name = null)
{
    if (! $name) {
        return \WS::$app->params['media']['baseUrl'];
    }
    return \WS::$app->params['media']['baseUrl'].'/'.$name;
}

function media_file($name = null)
{
    if (! $name) {
        return \WS::$app->params['media']['root'];
    }
    return \WS::$app->params['media']['root'].'/'.$name;
}

function domain()
{
    $parts = explode('.', $_SERVER["HTTP_HOST"]);

    if (count($parts) > 2) {
        array_splice($parts, 0, count($parts) - 2);
    }
    
    return '.'.implode('.', $parts);
}