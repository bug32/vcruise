<?php

namespace frontend\modules\api\controllers;

use yii\helpers\ArrayHelper;
use yii\rest\Controller;

class FilterController extends Controller
{
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                'cors' => [
//                    'Access-Control-Allow-Origin' => ['*'],
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    //Разрешает отдавать экзотические хедеры
                    'Access-Control-Expose-Headers' => ['*'],
                    //При использовании Access-Control-Allow-Credentials: true всегда
                    //используется Access-Control-Allow-Origin: домен — при использовании * браузер не получит ответ.
                    'Access-Control-Allow-Credentials' => false,
                    'Access-Control-Max-Age' => 3600,
                ],
            ],
        ]);
    }

    public function actionData(): array
    {

        return [];
    }

}