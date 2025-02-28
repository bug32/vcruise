<?php

namespace frontend\controllers;

use yii\db\Query;

class ShipsController extends BaseApiController
{

    public function actionIndex()
    {
        $q = new Query();

        $items = $q->select('id, name')
            ->from('ships')
            ->where(['status' => 10])
            ->all();

        return $this->sendResponse($items, 'success');
    }

}