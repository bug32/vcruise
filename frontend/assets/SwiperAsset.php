<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class SwiperAsset extends AssetBundle
{
    public $sourcePath = 'source/swiper';

    public $css = [
        'swiper.css',
        'swiper-theme.css',
    ];

    public $js = [
        'swiper.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];

}
