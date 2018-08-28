<?php
return \yii\helpers\ArrayHelper::merge([
    'id' => 'usleju',
    'language' => 'en-US',
    'bootstrap' => ['log'],
    'configuationData' => include(__DIR__.'/system.php'),
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
              [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning', 'info'],
                'categories' => ['dev']
              ]
            ]
        ],
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
        'area' => [
            'class' => '\common\component\Area',
            'maps' => include(__DIR__ . '/area.maps.php'),
        ],
        'mailer' => [
            'class' => '\common\cms\Mailer',
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
        'wxImage' => [
            'class' => 'common\supports\WXImage',
            'baseDir' => '',
            'baseUrl' => ''
        ],
        'shellMessage' => [
            'class' => 'common\supports\ShellMessage',
            'commandRootDir' => ''
        ],
        'i18n' => [ 
            'translations' => [
                '*'=>[
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable'=>'i18n_source_message',
                    'messageTable'=>'i18n_message',
                    'forceTranslation'=>true,
                    'sourceLanguage' => 'en-US',
                    'cachingDuration' => 86400,
                    'enableCaching' => true,
                ]
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ]
    ],
    'params' => [
        'email' => '',
        'frontend' => [
            'baseUrl' => ''
        ],
        'rets' => [
            'defPhotoUrl' => '',
        ],
        'media' => [
            'root' => '',
            'baseUrl' => ''
        ],
        'googleMap' => [
            'key' => ''
        ]
    ],
    'aliases'=>[
        '@COMMON'=>dirname(__DIR__)
    ]
], include(__DIR__.'/base.local.php'));
