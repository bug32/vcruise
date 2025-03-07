<?php

namespace frontend\controllers;

use frontend\models\cruises\Cruises;
use frontend\models\cruises\CruisesSearch;
use frontend\models\forms\CruisesFilterForm;
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
    public function actionView($id)
    {
        $model = Cruises::find()->where(['id' => $id])->select([
            'id',
            'name',
            'slug',
            'date_start',
            'date_end',
            'days',
            'nights',
            'min_price',
            'max_price',
        ])->one();

        $response = [
            'success' => TRUE,
            'data'    => $model,
            'total' => 38,
            'message' => 'success'
        ];

        return $this->serializeData( $response);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionCabins(){

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

        $filterForm = new CruisesFilterForm();
        $filterForm->load(Yii::$app->request->get());

        $cruises = new CruisesSearch();
        $items   = $cruises->filter($filterForm);

        return $this->sendResponse($items, 'success');
    }

}
