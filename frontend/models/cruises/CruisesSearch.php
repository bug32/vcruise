<?php

namespace frontend\models\cruises;

use yii\data\ActiveDataProvider;

class CruisesSearch extends CruisesResource
{
    public $type;

    public function rules(): array
    {
        return [
            ['limit', 'integer'],

            ['page', 'integer'],
            ['page', 'default', 'value' => 1],

            [['name'], 'string'],
            [['days'], 'integer'],
            ['type', 'string'],
        ];
    }

    public function search(array $params = []): ActiveDataProvider
    {

        $query = CruisesResource::find()->joinWith(['ship', 'type']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);


        $this->load($params, '');

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['>=', 'date_start_timestamp', time()]);

        if( $this->type ) {
            $type = Cruis
        }


        $dataProvider->pagination = ['pageSize' => $this->limit];
        return $dataProvider;
    }
}