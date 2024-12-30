<?php

namespace frontend\widgets\recommend;

use yii\base\Widget;

class MainRecommendWidget extends Widget
{

    public function init():void
    {
        parent::init();
    }

    public function run():string
    {

        return $this->render('main-recommend', [

        ]);
    }

}
