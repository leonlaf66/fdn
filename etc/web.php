<?php
$config = \yii\helpers\ArrayHelper::merge(include(__DIR__.'/base.php'), [
    'components' => [
        'session' => [
            'useCookies' => true,
            'cookieParams' => [
                'domain' => '.usleju.local',
                'httpOnly' => true,
            ],
        ]
    ],
    'params' => [
        'iconfontUrl' => '//at.alicdn.com/t/font_318117_ufr0yy7nv5n1xlxr.css',
        'webApp' => [
            'baseUrl' => ''
        ],
        'resource' => [
            'root' => '',
            'baseUrl' => ''
        ],
        'wechat' => [
            'appId' => '',
            'appSecret' => ''
        ]
    ]
], include(__DIR__.'/web.local.php'));

return $config;