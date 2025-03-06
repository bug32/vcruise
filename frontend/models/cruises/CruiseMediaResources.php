<?php

namespace frontend\models\cruises;

use common\models\CruiseMedias;
use Yii;

class CruiseMediaResources extends CruiseMedias
{

    public function fields(): array
    {
        return [
            'alt',
            'name',
            'mime_type',
            'url' => function ($model) {
                return Yii::getAlias('@imageHost').$model->url;
            },
            'priority',
        ];

    }

}