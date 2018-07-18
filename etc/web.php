<?php
return [
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
        'iconfontUrl' => '//at.alicdn.com/t/font_318117_dc83cwo65e.css',
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
];
