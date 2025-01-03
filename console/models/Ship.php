<?php

namespace console\models;

use Random\RandomException;

class Ship extends \common\models\Ship
{

    /**
     * @throws RandomException
     */
    public static function createEmpty(): int
    {

        $tempName = md5(time().''.random_int(0, 99999));
        $model = new self();
        $model->name = $tempName;
        $model->slug = $tempName;
        $model->operatorId = 1;
        $model->typeId = 1;
        $model->status = 0;
        $model->priority = 0;
        if ($model->save()){
            return $model->id;
        }

        throw new RandomException('Не удалось создать корабль');
    }

}