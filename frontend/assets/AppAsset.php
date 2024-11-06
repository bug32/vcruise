<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'scss/project.css'
    ];
    public $js = [
        'source/silicon/js/theme-build.js',
        'js/theme-switcher.js',
    ];
    public $depends = [
        YiiAsset::class,
        FontAwesomeAsset::class
    ];
}
