<?php

namespace frontend\widgets\MainFilterWidget;

use yii\base\Widget;

class MainFilterWidget extends Widget
{

    public function init():void
    {
        parent::init();
    }

    public function run():string
    {
        return $this->render('index', [

        ]);
    }
}
