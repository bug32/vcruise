<?php

namespace frontend\models\cruises;

use common\models\CityMedias;
use frontend\models\ships\ShipsShort;

class Cruises extends \common\models\Cruises
{

    public function getShipShort(): \yii\db\ActiveQuery
    {
        return $this->hasOne(ShipsShort::class, ['id' => 'ship_id']);
    }

    public function getPhotos()
    {
        return $this->hasMany(CruiseMediaResources::class, ['cruise_id' => 'id']);
         //   ->orderBy([CruiseMediaResources::tableName() . '.priority' => SORT_DESC]);
    }

    public function getCityMedias()
    {
        return $this->hasMany(CityMediaResources::class, ['city_id' => 'city_start_id']);
    }

}