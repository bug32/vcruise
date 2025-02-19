<?php

namespace frontend\models\ships;

class ShipsResource extends Ships
{

    public function fields(): array
    {
        return [
            'id',
            'name',
            'slug',
            'photos' => function () {
                return [];
            }
        ];
    }
}