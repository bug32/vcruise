<?php

namespace frontend\models\ships;

class ShipResource extends Ship
{

    public function fields(): array
    {
        return [
            'id',
            'name',
            'slug',
            'photos' => function () {
                return $this->shipMedia;
            }
        ];
    }
}