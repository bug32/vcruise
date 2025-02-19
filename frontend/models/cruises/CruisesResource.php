<?php

namespace frontend\models\cruises;

use frontend\models\ships\ShipsResource;
use yii\db\ActiveQuery;

/*
 *
 * @property ShipResource $ship
 * */
class CruisesResource extends Cruises
{

    public function fields(): array
    {
        return [
            'id',
            'name',
            'slug',
            'description',
            'route',
            'route_short',
            'date_start',
            'date_end',
            'days',
            'nights',
            'min_price',
            'currency',
            'free_cabins',

            'ship'
        ];
    }

    public function extraFields(): array
    {
        return [
            'ship'
        ];
    }


    /**
     * Gets query for [[Ship]].
     *
     * @return ActiveQuery
     */
    public function getShip(): ActiveQuery
    {
        return $this->hasOne(ShipsResource::class, ['id' => 'ship_id']);
    }

}