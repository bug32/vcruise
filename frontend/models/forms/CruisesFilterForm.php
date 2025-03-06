<?php

namespace frontend\models\forms;

use yii\base\Model;


/*
 *
 * @property ?string $dateStart // Дата начала интервала
 * @property ?string $dateEnd  // Дата конца интервала
 * @property ?int $shipId // Теплоход
 * @property ?int $days // Продолжительность в днях
 * @property ?int $cityId // Город начала круиза
 * @property ?int $priceMax // Максимальная цена
 * @property ?int $priceMin // Минимальная цена
 * @property ?int $isFreeCabins
 *
 * */

class CruisesFilterForm extends Model
{

    public function rules(): array
    {
        return [
            [['dateStart', 'date_end'], 'string'],
            [['shipId', 'days', 'cityId'], 'integer'],
            [['priceMax', 'priceMin'], 'integer'],
            [['isFreeCabins'], 'integer'],
            ['isFreeCabins', 'default', 'value' => 1]
        ];
    }
}