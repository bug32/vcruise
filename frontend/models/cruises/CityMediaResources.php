<?php

namespace frontend\models\cruises;

use common\models\CityMedias;
use Yii;

class CityMediaResources extends CityMedias
{

    public function fields():array
    {
        return [
            'id',
            'alt',
            'mime_type',
            'url' => function ($model) {
                return Yii::getAlias('@imageHost').$model->url;
            },
            'priority',
        ];
    }

}