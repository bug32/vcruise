<?php

namespace frontend\controllers;

use yii\web\Controller;

class CruiseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}