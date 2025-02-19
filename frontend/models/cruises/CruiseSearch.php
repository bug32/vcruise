<?php

namespace frontend\models\cruises;

use yii\data\ActiveDataProvider;

class CruiseSearch extends CruiseResource
{
    public $limit;
    public $page;

    public function rules(): array
    {
        return [
            ['limit', 'integer'],

            ['page', 'integer'],
            ['page', 'default', 'value' => 1],

            [['name'], 'string'],
            [['days'], 'integer'],
        ];
    }

    public function search(array $params = []): ActiveDataProvider
    {

        $query = CruiseResource::find()->joinWith(['ship','type']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);


        $this->load($params, '');

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['>=', 'date_start_timestamp', time()]);



        $dataProvider->pagination = ['pageSize' => $this->limit];
        return $dataProvider;
    }
}