<?php

use yii\web\UrlNormalizer;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'vcruis.online',
    'name'                => 'vcruis.online',
    'basePath'            => dirname(__DIR__),
    'language'            => 'ru-RU',
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute'        => 'site/index',
    'homeUrl'             => '/',
    'components'          => [
        'request'      => [
            'baseUrl'   => '/',
            'csrfParam' => '_csrf-frontend',
        ],
        'assetManager' => [
            'appendTimestamp' => TRUE,
            'bundles'         => [
                '\yii\bootstrap5\BootstrapPluginAsset' => [
                    'js' => []
                ],
                'yii\bootstrap5\BootstrapAsset'        => [
                    'css' => [],
                    //   'scss' => [],
                ],
            ],
        ],
        'view'         => [
            'class'           => frontend\components\View::class,
        ],

        'user'         => [
            'identityClass'   => \common\models\User::class,
            'enableAutoLogin' => TRUE,
            'identityCookie'  => ['name' => '_identity-frontend', 'httpOnly' => TRUE],
        ],
        'session'      => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'cruise-frontend',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'       => \yii\log\FileTarget::class,
                    'levels'      => ['error', 'warning', 'info'],
                    'categories'  => ['error'],
                    'logFile'     => '@frontend/runtime/logs/error/error.log',
                    'logVars'     => ['_GET', '_POST'],
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 1,
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'class'               => yii\web\UrlManager::class,
            'baseUrl'             => '/',
            'enablePrettyUrl'     => TRUE,
            'showScriptName'      => FALSE,
            'enableStrictParsing' => FALSE,
            //   'suffix' => '/',
            'normalizer'          => [
                'class' => UrlNormalizer::class,
            ],
            'rules'               => [
                '/'     => 'site/index',
                'login' => 'site/login',

                'cruises' => 'cruise/index',
                'cruises/<action:\w+>' => 'cruises/<action>',
                'cruises/<slug>-<id>' => 'cruises/view',

                '<controller:\w+>/<id:\d+>'              => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
            ],
        ],

    ],
    'params'              => $params,
];
