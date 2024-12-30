<?php

namespace frontend\widgets\HelpWidget;

use frontend\models\forms\RequestCellForm;
use yii\base\Widget;

class RequestFormWidget extends Widget
{
    public function init(): void
    {
        parent::init();
    }

    public function run(): string
    {

        $model = new RequestCellForm();

        return $this->render('request-form', [
            'model' => $model,
        ]);
    }

}
