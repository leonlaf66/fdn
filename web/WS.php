<?php
class WS extends Yii
{
    public static function t($category, $message, $params = [], $language = null)
    {
        if(self::$app->id === 'usleju-admin') return null;
        $isUiLang = true;

        if(substr($category, 0, 1) === '@') {
            $category = substr($category, 1);
            $isUiLang = false;
            \module\cms\helpers\Language::submit($category, $message, $message, 'zh-CN');
        }

        $result = parent::t($category, $message, $params, $language);

        if(! \Yii::$app->translationStatus) {
            return $result;
        }

        $message = htmlspecialchars($message);
        return $isUiLang ? "<t data-type=\"{$category}\" data-source=\"{$message}\">{$result}</t>" : $result;
    }

    public static function lang($type, $r=false)
    {
        $t = function($text, $params=[], $return=false) use($type, $r) {
            $result = WS::t($type, $text, $params);
            if($r || $return) {
                return $result;
            }
            echo $result; 
        };
        return $t;
    }

    public static function text($texts)
    {
        $texts = array_merge(['en-US'=>'', 'zu-CN'=>''], $texts);
        return $texts[\Yii::$app->language];
    }

    public static function langText($enText, $cnText)
    {
        return \WS::$app->language === 'zh-CN' && ! empty($cnText) ? $cnText : $enText;
    }

    public static function isChinese()
    {
        return \WS::$app->language === 'zh-CN';
    }

    public static function getStaticData($name)
    {
        return include(\WS::$app->basePath . "/data/{$name}.php");
    }

    public static function share($key, $value = null)
    {
        static $data = [];

        if ($value) {
            $data[$key] = $value;
        } else {
            return $data[$key] ?? null;
        }
    }
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require(APP_ROOT . '/vendor/yiisoft/yii2/classes.php');
Yii::$container = new yii\di\Container();