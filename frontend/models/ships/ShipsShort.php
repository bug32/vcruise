<?php

namespace frontend\models\ships;

use common\models\ShipMedias;

class ShipsShort extends Ships
{

    public function fields(): array
    {
        return [
          'id',
          'name',
          'slug',
          'photoMain'
        ];
    }

    public function getPhotoMain(): \yii\db\ActiveQuery
    {
        return $this->hasOne(ShipMediaResources::class, ['ship_id' => 'id'])
            ->andWhere(['key' => ShipMedias::KEY_PHOTO]);
    }

}