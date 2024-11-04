<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = 'source/font-awesome';

    public $cssOptions = ['rel' => 'preload', 'as' => 'style', 'onload' => 'this.onload=null;this.rel="stylesheet"'];

    public $css = [
        'css/all.min.css',
    ];

    public $publishOptions = [
        'only' => [
            'all.min.css',
            'webfonts/*',
        ]
    ];
}
