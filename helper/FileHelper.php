<?php
namespace common\helper;

class FileHelper extends \yii\helpers\ArrayHelper
{
    public static function searchFiles($root, $callback)
    {
        foreach (glob($root.'/*') as $single) {
            if (is_dir($single)) { 
                self::searchFiles($single, $callback);
            }
            else {
                $callback($single);
            } 
        }
    }
}