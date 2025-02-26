<?php

namespace frontend\controllers;

use frontend\models\cruises\CruisesSearch;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;

class CruisesController extends BaseApiController
{
    /**
     * @throws InvalidConfigException
     */
    public function actionIndex(): mixed
    {
      //  $cruise = new CruisesSearch();
      //  $items  = $cruise->search(\Yii::$app->request->get());

        //   $cruise = CruiseResource::find()->joinWith('ship')->limit(40)->all();

        return $this->sendResponse([], 'success');
    }


    /**
     * @throws InvalidConfigException
     */
    public function actionSearch(): mixed
    {

        $cruise = new CruisesSearch();
        $items  = $cruise->search(\Yii::$app->request->get());

        return $this->sendResponse($items, 'success');
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function actionGet()
    {
        $cruises = new CruisesSearch();
        $items = $cruises->filter( Yii::$app->request->get());

        return $this->sendResponse($items, 'success');
    }

}
