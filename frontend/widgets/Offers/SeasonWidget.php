<?php

namespace frontend\widgets\Offers;

use yii\base\Widget;

class SeasonWidget extends Widget
{

    public function init(): void
    {
        parent::init();
    }

    public function run(): string
    {
        return $this->render('main-season', [
            'widget' => $this,
            'id' => $this->getId()
        ]);
    }

}
