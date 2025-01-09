<?php

namespace console\services\providers\infoflot\parsers;

use console\models\City;
use console\services\providers\infoflot\InfoflotAPI;
use Psy\Exception\ThrowUpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class OtherParser extends InfoflotAPI
{

    public function runService(): void
    {
        $services = $this->getServices();

        if (empty($services['data'])) {
            return;
        }

        foreach ($services['data'] as $service) {

            try {

                $icon = '';
                if (empty($service['icon'])) {
                    $icon = $this->saveFile($service['icon'], 'services');
                }

                $params = [
                    'id'          => $service['id'],
                    'name'        => $service['name'],
                    'slug'        => Inflector::slug($service['name']),
                    'icon'        => $icon,
                    'description' => $this->clearText(strip_tags($service['description'])),
                    'priority'    => $service['priority'],
                ];

                \Yii::$app->db->createCommand()->insert('service', $params)->execute();
            } catch (\Throwable $e) {
                echo 'Error ID: ' . $service['id'] . PHP_EOL;
                // echo 'Error ' . $e->getMessage() . PHP_EOL;
            }
        }

    }

    public function runRiver(): void
    {
        $rivers = $this->getRivers();
        if (empty($rivers['data'])) {
            return;
        }
        foreach ($rivers['data'] as $river) {
            try {
                $params = [
                    'id'   => $river['id'],
                    'name' => $river['name'],
                    'slug' => Inflector::slug($river['name']),
                ];
                \Yii::$app->db->createCommand()->insert('river', $params)->execute();
            } catch (\Throwable $e) {
                echo 'Error ID: ' . $river['id'] . PHP_EOL;
                // echo 'Error ' . $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function runCity(): void
    {
        $cities = $this->getCities();
        if (empty($cities['data'])) {
            return;
        }

        $citiesIDs = \Yii::$app->db->createCommand('SELECT * FROM city')->queryAll();
        $citiesIDs = ArrayHelper::index($citiesIDs, 'id');

        foreach ($cities['data'] as $city) {
            //  try {
            if (!empty($citiesIDs[$city['id']])) {
                continue;
            }

            $cityDetail = $this->request('/cities/' . $city['id']);

            $model              = new City();
            $model->id          = $cityDetail['id'];
            $model->name        = $cityDetail['name'];
            $model->slug        = Inflector::slug($cityDetail['name']);
            $model->description = $this->clearText($cityDetail['description']);
            $model->country_id  = 0;
            if ($model->save()) {
                if (empty($cityDetail['photos'])) {
                    continue;
                }
                foreach ($cityDetail['photos'] as $photo) {
                    $params = [
                        'city_id' => $model->id,
                        'url'     => $this->saveFile($photo['filename'], 'cities/' . $model->id . '/gallery'),
                    ];
                    \Yii::$app->db->createCommand()->insert('city_media', $params)->execute();
                }
            } else {
                echo 'Error ID: ' . $city['id'] . PHP_EOL;
                continue;
            }

            if (!empty($cityDetail['photo']['city_img']['filename'])) {
                $params = [
                    'city_id' => $model->id,
                    'url'     => $this->saveFile($cityDetail['photo']['city_img']['filename'], 'cities/' . $model->id . '/gallery'),
                ];
                \Yii::$app->db->createCommand()->insert('city_media', $params)->execute();
            }
            /*
        } catch (\Throwable $e) {
            echo 'Error ID: ' . $city['id'] . PHP_EOL;
             echo 'Error ' . $e->getMessage() .' '.$e->getLine(). PHP_EOL;
             die();
        }
            */
        }
    }

    public function runCountry(): void
    {
        $countries = $this->getCountries();

        if (empty($countries['data'])) {
            return;
        }

        foreach ($countries['data'] as $country) {
            try {


                $params = [
                    'id'   => $country['id'],
                    'name' => $country['name'],
                    'slug' => Inflector::slug($country['name']),
                ];

                \Yii::$app->db->createCommand()->insert('country', $params)->execute();
            } catch (\Throwable $e) {
                echo 'Error ID: ' . $country['id'] . PHP_EOL;
                continue;
            }
        }

    }

    protected function getCountries(): array
    {
        return $this->request('/countries');

    }

}