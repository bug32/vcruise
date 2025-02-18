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
    'bootstrap'           => [
        'log',
        \frontend\modules\api\Bootstrap::class,
    ],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute'        => 'site/index',
    'homeUrl'             => '/',
    'modules'             => [
        'Api' => [
            'class' => \frontend\modules\api\Module::class,
        ],
    ],
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
            'enableMinify'    => YII_ENV_PROD,
            'concatCss'       => TRUE,                     // concatenate css
            'minifyCss'       => TRUE,                     // minification css
            'concatJs'        => TRUE,                     // concatenate js
            'minifyJs'        => TRUE,                     // minification js
            'minifyOutput'    => TRUE,                     // minification result html page
            'webPath'         => '@web',                   // path alias to web base
            'basePath'        => '@webroot',               // path alias to web base
            'minifyPath'      => '@webroot/minify',        // path alias to save minify result
            'jsPosition'      => [\yii\web\View::POS_END], // positions of js files to be minified
            'forceCharset'    => 'UTF-8',                  // charset forcibly assign, otherwise will use all the files found charset
            'expandImports'   => TRUE,                     // whether to change @import on content
            'compressOptions' => ['extra' => TRUE],        // options for compress
            'excludeFiles'    => [
                'jquery.js',   // exclude this file from minification
                'app-[^.].js', // you may use regexp
            ],
            'excludeBundles'  => [
                //  \app\helloworld\AssetBundle::class, // exclude this bundle from minification
            ],
        ],

        'user'         => [
            'identityClass'   => 'common\models\User',
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

                'cruises' => 'cruise/index', // страница фильтрации

                '<controller:\w+>/<id:\d+>'              => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'          => '<controller>/<action>',

            ],
        ],

    ],
    'params'              => $params,
];
