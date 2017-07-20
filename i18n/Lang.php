<?php
namespace common\i18n;

class Lang extends \yii\i18n\I18N 
{
    private static $_i10n = null;
    private static $_sourceLanguage = 'en-US';
    private static $_language = 'en-US';
    private static $_translationStatus = false;

    public static function init($options, $language, $translationStatus)
    {
        self::$_sourceLanguage = $sourceLanguage;
        self::$_language = $language;
        self::$_translationStatus = $translationStatus;
    }

    public static t()
    {
        return self::i18n->translate($category, $message, $params, $language ?: static::$app->language);
    }
}