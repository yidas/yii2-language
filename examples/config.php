<?php

return [
    'id' => 'app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'lang'],
    // Language
    'language' => 'en-US',
    'sourceLanguage' => 'en-US',
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        /**
         * Yii 2 Language Extension
         * 
         * @see https://github.com/yidas/yii2-language
         */
        'lang' => [
            'class' => 'yidas\components\Language',
            'languages' => [
                0 => 'en-US',
                1 => 'zh-TW',
                2 => 'zh-CN',
            ],
            'maps' => [
                'html' => [
                    0 => 'en',
                    1 => 'zh-Hant',
                    2 => 'zh-Hans',
                ],
            ],
            // 'storage' => 'session',
            // 'storageKey' => 'language',
        ],
    ],
    'params' => $params,
    // BeforeAction for globally changing language
    'on beforeAction' => function ($event) {
        // Always fetch language from get-parameter
        $lang = \Yii::$app->request->get('lang');
        // Set to given language with get-parameter
        if ($lang) {
            $result = \Yii::$app->lang->set($lang);
        }
    },
];
