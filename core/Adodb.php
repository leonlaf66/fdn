<?php
namespace common\core;

require __DIR__.'/adodb5/adodb.inc.php';

class Adodb
{
    public static function getMlsDb() {
        $db = \NewADOConnection('ado_mssql');
        var_dump($db);exit;
        $db->Connect('vendors.mlspin.com', 'AN2591', 'phrep1', 'VENDORS.MLSPIN.COM')or die("df"); //连接MySQL数据库
        return $db;
    }
}