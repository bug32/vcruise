<?php

namespace frontend\models\ships;

use common\models\ShipMedias;
use Yii;

class ShipMediaResources extends ShipMedias
{

    public function fields():array
    {
        return [
            'alt',
            'name',
            'url' => function ($model) {
                return Yii::getAlias('@imageHost').$model->url;
            },
        ];
    }
}