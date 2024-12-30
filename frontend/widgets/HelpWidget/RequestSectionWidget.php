<?php

namespace frontend\widgets\HelpWidget;

use yii\base\Widget;

class RequestSectionWidget extends Widget
{

    public function init():void
    {
        parent::init();
    }


    public function run():string
    {
        return $this->render('request-section',[]);
    }

}
