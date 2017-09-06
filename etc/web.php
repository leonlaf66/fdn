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
        'iconfontUrl' => '//at.alicdn.com/t/font_318117_0kw9eie5sp6nu3di.css',
        'resource' => [
            'root' => '',
            'baseUrl' => ''
        ]
    ]
], include(__DIR__.'/web.local.php'));

return $config;