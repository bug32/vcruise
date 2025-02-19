<?php

namespace frontend\models\cruises;

use frontend\models\ships\ShipResource;

/*
 *
 * @property ShipResource $ship
 * */
class CruiseResource extends Cruise
{

    public $expand='ship';
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
     * @return \yii\db\ActiveQuery
     */
    public function getShip(): \yii\db\ActiveQuery
    {
        return $this->hasOne(ShipResource::class, ['id' => 'ship_id']);
    }

}