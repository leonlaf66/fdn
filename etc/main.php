<?php
return \yii\helpers\ArrayHelper::merge([
    'bootstrap' => ['log'],
    'sourceLanguage'=>'en-US',
    'language'=>'en-US',
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
            'viewPath'=>'@common/mail',
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
                'from'=>['admin@wesnail.com'=>'Wesnail']
            ]
        ],
        'session' => [
            'useCookies' => true,
            'cookieParams' => [
                'domain' => '.usleju.local',
                'httpOnly' => true,
            ],
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
        'iconfontUrl' => '//at.alicdn.com/t/font_fygpvhhurh589f6r.css',
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
    ]
], include(__DIR__.'/local.php'));
