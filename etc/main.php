<?php
return \yii\helpers\ArrayHelper::merge([
    'domain' => '',
    'configuationData' => include(__DIR__.'/system.php'),
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=;dbname=usleju',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix'=>''
        ],
        'mlsdb'=>[
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=;dbname=realestate',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix'=>''
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'viewPath'=>'@COMMON/mail',
            'transport'=>[
                'class' => 'Swift_SmtpTransport',    
                'host' => 'smtp.qq.com',    
                'username' => 'admin@wesnail.com',    
                'password' => 'Leon123',    
                'port' => '25',    
                'encryption' => 'tls'
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>[]
            ]
        ],
        'session' => [
            'useCookies' => true,
            'cookieParams' => [
                'domain' => '.usleju.local',
                'httpOnly' => true,
            ],
        ],
        'wxImage' => [
            'class' => 'common\web\WXImage',
            'baseDir' => '',
            'baseUrl' => ''
        ],
        'shellMessage' => [
            'class' => 'common\web\ShellMessage',
            'commandRootDir' => ''
        ],
        'i18n' => [ 
            'translations' => [
                '*'=>[
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable'=>'i18n_source_message',
                    'messageTable'=>'i18n_message',
                    'forceTranslation'=>true,
                    'sourceLanguage' => 'en',
                    'cachingDuration' => 86400,
                    'enableCaching' => false,
                ]
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ]
    ],
    'params' => [
        'email' => 'admin@usleju.com',
        'iconfontUrl' => '//at.alicdn.com/t/font_318117_0kw9eie5sp6nu3di.css',
        'frontend' => [
            'baseUrl' => 'http://www.usleju.local'
        ],
        'systemConfigData' => include(__DIR__.'/system.php'),
        'rets' => [
            'defPhotoUrl' => 'http://media.usleju.local/rets/placeholder.jpg',
        ],
        'media' => [
            'root' => '/Develops/branches/usleju/medias',
            'baseUrl' => 'http://media.usleju.local'
        ]
    ],
    'aliases'=>[
        '@COMMON'=>dirname(__DIR__)
    ]
], include(__DIR__.'/local.php'));
