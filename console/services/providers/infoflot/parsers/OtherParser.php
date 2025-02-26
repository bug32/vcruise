<?php

namespace console\services\providers\infoflot\parsers;

use console\models\City;
use console\services\providers\infoflot\InfoflotAPI;
use Yii;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class OtherParser extends InfoflotAPI
{

    protected array $cityNames    = [];
    protected array $countryNames = [];

    public function runService(): void
    {
        $services = $this->getServices();

        if (empty($services['data'])) {
            return;
        }

        foreach ($services['data'] as $service) {

            try {



                $icon = '';
                if (!empty($service['icon'])) {
                    $icon = $this->saveFile($service['icon'], 'services');
                }

                $params = [
                    'id'          => $service['id'],
                    'name'        => $service['name'],
                    'icon'        => $icon,
                    'description' => $this->clearText(strip_tags($service['description'])),
                    'priority'    => $service['priority'],
                ];

                $temp = Yii::$app->db->createCommand('SELECT id FROM services WHERE id = :id', [
                    ':id' => $service['id']
                ])->queryOne();
                if ($temp) {

                    if( empty($icon) ) {
                        continue;
                    }

                    Yii::$app->db->createCommand()->update('services', [
                        'icon'        => $icon,
                    ], 'id = :id', [':id' => $service['id']])->execute();
                    continue;
                }else {
                    \Yii::$app->db->createCommand()->insert('services', $params)->execute();
                }

            } catch (\Throwable $e) {
                echo 'Error ID: ' . $service['id'] . $e->getMessage(). PHP_EOL;
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
                ];
                \Yii::$app->db->createCommand()->insert('rivers', $params)->execute();
            } catch (\Throwable $e) {
                echo 'Error ID: ' . $river['id'] . PHP_EOL;
                echo 'Error ' . $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function runCity(): void
    {
        $cities = $this->getCities();
        if (empty($cities['data'])) {
            return;
        }

        $citiesIDs = \Yii::$app->db->createCommand('SELECT * FROM cities')->queryAll();
        $citiesIDs = ArrayHelper::index($citiesIDs, 'id');

        foreach ($cities['data'] as $city) {
            //  try {
            if (!empty($citiesIDs[$city['id']])) {
                continue;
            }

            try {
                $cityDetail = $this->request('/cities/' . $city['id']);
            } catch (\Throwable $e) {
                continue;
            }

            $cityName = trim($cityDetail['name']);
            $citySlug = Inflector::slug($cityDetail['name']);
            $model    = City::findOne(['slug' => $citySlug]);
            if (!$model) {
                $model              = new City();
                $model->name        = $cityName;
                $model->slug        = $citySlug;
                $model->description = $this->clearText($cityDetail['description']);
                $model->country_id  = 1;
                if (!$model->save()) {
                    echo 'Error ID: ' . $city['id'] . PHP_EOL;
                    echo 'Error ' . print_r($model->errors, true) . PHP_EOL;
                    continue;
                }

                \Yii::$app->db->createCommand()->insert('provider_combinations', [
                    'foreign_id'    => $cityDetail['id'],
                    'internal_id'   => $model->id,
                    'model_name'    => self::PROVIDER_MODEL_NAME_CITY,
                    'provider_name' => self::PROVIDER_NAME,
                ])->execute();

                if (!empty($cityDetail['photo']['city_img']['filename'])) {
                    $params = [
                        'city_id' => $model->id,
                        'url'     => $this->saveFile($cityDetail['photo']['city_img']['filename'], 'cities/' . $model->id . '/gallery'),
                    ];
                    \Yii::$app->db->createCommand()->insert('city_medias', $params)->execute();
                }

                if (!empty($cityDetail['photos'])) {


                    foreach ($cityDetail['photos'] as $photo) {
                        $params = [
                            'city_id' => $model->id,
                            'url'     => $this->saveFile($photo['filename'], 'cities/' . $model->id . '/gallery'),
                        ];
                        \Yii::$app->db->createCommand()->insert('city_medias', $params)->execute();
                    }
                }
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
                $transaction = Yii::$app->db->beginTransaction();
                $slug        = Inflector::slug($country['name']);

                $countriesID = Yii::$app->db->createCommand('SELECT id FROM countries WHERE slug = :slug', [
                    ':slug' => $slug,
                ])->queryOne();

                $params = [
                    'id'   => $country['id'],
                    'name' => $country['name'],
                    'slug' => $slug,
                ];

                if (empty($countriesID)) {
                    Yii::$app->db->createCommand()->insert('countries', $params)->execute();
                    $countriesID = Yii::$app->db->createCommand('SELECT id FROM countries WHERE slug = :slug', [
                        ':slug' => $slug,
                    ])->queryOne();
                }

                if (!empty($countriesID)) {
                    $countriesID = $countriesID['id'];
                } else {
                    throw new \RuntimeException('Country not found');
                }

                Yii::$app->db->createCommand()->insert('provider_combinations', [
                    'foreign_id'    => $country['id'],
                    'internal_id'   => $countriesID,
                    'provider_name' => self::PROVIDER_NAME,
                    'model_name'    => self::PROVIDER_MODEL_NAME_COUNTRY
                ])->execute();

                $transaction->commit();

            } catch (\Throwable $e) {
                $transaction->rollBack();
                echo 'Error ID: ' . $country['id'] . PHP_EOL;
                continue;
            }

        }

    }

    protected function getCountries(): array
    {
        return $this->request('/countries');

    }

    public function runPort(): void
    {
        // Парсим порты и доки

        $ports = $this->request('/ports');
        if (empty($ports['data'])) {
            return;
        }

        foreach ($ports['data'] as $port) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $portSlug = Inflector::slug($port['name'] . '-' . $port['city']);
                $temp     = Yii::$app->db->createCommand('SELECT id FROM ports WHERE slug = :slug', [
                    ':slug' => $portSlug,
                ])->queryOne();
                if (!empty($temp)) {
                    $transaction->commit();
                    continue;
                }

                $countryId = $this->searchCountryByName($port['country']);
                if (empty($countryId)) {
                    $countryId = $this->addCountry($port['country']);
                }

                $cityId = $this->searchCityByName($port['city']);
                if (empty($cityId)) {
                    $cityId = $this->addCity($port['city'], $countryId);
                }

                Yii::$app->db->createCommand()->insert('{{%ports}}', [
                    'name'       => $port['name'],
                    'slug'       => $portSlug,
                    'city_id'    => $cityId,
                    'country_id' => $countryId,
                ])->execute();

                $portId = Yii::$app->db->getLastInsertID();
                Yii::$app->db->createCommand()->insert('provider_combinations', [
                    'foreign_id'    => $port['id'],
                    'internal_id'   => $portId,
                    'model_name'    => self::PROVIDER_MODEL_NAME_PORT,
                    'provider_name' => self::PROVIDER_NAME,
                ])->execute();

                if (empty($port['docks'])) {
                    $transaction->commit();
                    continue;
                }

                foreach ($port['docks'] as $dock) {
                    Yii::$app->db->createCommand()->insert('docks', [
                        'port_id'     => $portId,
                        'name'        => $dock['name'],
                        'address'     => $dock['yandex_address'] ?? $dock['address'],
                        'coordinates' => $dock['yandex_coordinates'],
                    ])->execute();
                    $dockId = Yii::$app->db->getLastInsertID();

                    Yii::$app->db->createCommand()->insert('provider_combinations', [
                        'foreign_id'    => $dock['id'],
                        'internal_id'   => $dockId,
                        'model_name'    => self::PROVIDER_MODEL_NAME_DOCK,
                        'provider_name' => self::PROVIDER_NAME,
                    ])->execute();
                }

                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();
                echo 'Error ID: ' . $port['id'] . PHP_EOL;
                echo $e->getMessage() . PHP_EOL;
                echo $e->getTraceAsString() . PHP_EOL;
                die();
                continue;
            }


        }
    }

    protected function initCityByName(): void
    {
        if (!empty($this->cityNames)) {
            return;
        }

        $sql = "SELECT pc.internal_id, cities.name 
                FROM cities 
                LEFT JOIN provider_combinations pc ON cities.id = pc.internal_id
                WHERE  pc.model_name='city' AND pc.provider_name='infoflot'
                GROUP BY cities.name, pc.internal_id";

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        if (empty($data)) {
            return;
        }

        $this->cityNames = ArrayHelper::index($data, 'name');
    }

    protected function searchCityByName(mixed $city): ?string
    {
        if (empty($this->cityNames)) {
            $this->initCityByName();
        }

        $city = trim($city);

        return $this->cityNames[$city]['internal_id'] ?? NULL;
    }

    protected function initCountryByName(): void
    {
        if (!empty($this->countryNames)) {
            return;
        }

        $sql = "SELECT pc.internal_id, countries.name 
                FROM provider_combinations pc
                LEFT JOIN countries ON countries.id = pc.internal_id
                WHERE pc.model_name='country' AND pc.provider_name='infoflot'
                GROUP BY countries.name, pc.internal_id";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        if (empty($data)) {
            return;
        }

        $this->countryNames = ArrayHelper::index($data, 'name');
    }

    protected function searchCountryByName(string $country): ?string
    {
        if (empty($this->countryNames)) {
            $this->initCountryByName();
        }

        $country = trim($country);

        return $this->countryNames[$country]['internal_id'] ?? NULL;
    }

    protected function addCountry(mixed $country)
    {
        $slug = Inflector::slug($country);

        $temp = Yii::$app->db->createCommand('SELECT id FROM countries WHERE slug = :slug', [
            ':slug' => $slug,
        ])->queryOne();
        if ($temp) {
            return $temp['id'];
        }

        Yii::$app->db->createCommand()->insert('{{%countries}}', [
            'name' => $country,
            'slug' => $slug,
        ])->execute();

        $countryId = Yii::$app->db->createCommand('SELECT id FROM countries WHERE slug= :slug', [
            ':slug' => $slug,
        ])->queryOne();
        return $countryId['id'];
    }

    protected function addCity(string $city, int $countryId)
    {
        $slug = Inflector::slug($city);

        $temp = Yii::$app->db->createCommand('SELECT id FROM cities WHERE slug = :slug', [
            ':slug' => $slug,
        ])->queryOne();
        if ($temp) {
            return $temp['id'];
        }

        Yii::$app->db->createCommand()->insert('{{%cities}}', [
            'name'       => $city,
            'slug'       => $slug,
            'country_id' => $countryId,
        ])->execute();

        $cityId = Yii::$app->db->createCommand('SELECT id FROM cities WHERE slug=:slug', [
            'slug' => $slug,
        ])->queryOne();

        return $cityId['id'];
    }

    public function runRoute()
    {
        $routes = $this->request('/popular-routes');
        if (empty($routes['data'])) {
            return;
        }

        foreach ($routes['data'] as $route) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $slug = Inflector::slug($route['name']);
                $temp = Yii::$app->db->createCommand('SELECT id FROM popular_routes WHERE slug = :slug', [
                    ':slug' => $slug,
                ])->queryOne();

                if ($temp) {
                    $transaction->commit();
                    continue;
                }

                Yii::$app->db->createCommand()->insert('{{%popular_routes}}', [
                    'name' => $route['name'],
                    'slug' => $slug,
                ])->execute();

                $routeId = Yii::$app->db->getLastInsertID();

                Yii::$app->db->createCommand()->insert('provider_combinations', [
                    'foreign_id'    => $route['id'],
                    'internal_id'   => $routeId,
                    'model_name'    => self::PROVIDER_MODEL_POPULAR_ROUTES,
                    'provider_name' => self::PROVIDER_NAME,
                ])->execute();

                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();
            }
        }
    }

    public function runRegions()
    {
        $regions = $this->request('/regions');
        if (empty($regions['data'])) {
            return;
        }

        foreach ($regions['data'] as $region) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $slug = Inflector::slug($region['name']);
                $temp = Yii::$app->db->createCommand('SELECT id FROM regions WHERE slug = :slug', [
                    ':slug' => $slug,
                ])->queryOne();

                if ($temp) {
                    $transaction->commit();
                    continue;
                }

                Yii::$app->db->createCommand()->insert('{{%regions}}', [
                    'name' => $region['name'],
                    'slug' => $slug,
                ])->execute();

                $regionId = Yii::$app->db->getLastInsertID();

                Yii::$app->db->createCommand()->insert('provider_combinations', [
                    'foreign_id'    => $region['id'],
                    'internal_id'   => $regionId,
                    'model_name'    => self::PROVIDER_MODEL_REGIONS,
                    'provider_name' => self::PROVIDER_NAME,
                ])->execute();

                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();
            }
        }
    }

    public function runPlaces()
    {
        $places = $this->request('/public-places');
        if (empty($places['data'])) {
            return;
        }

        foreach ($places['data'] as $place) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $temp = Yii::$app->db->createCommand('
                   SELECT internal_id 
                   FROM provider_combinations 
                   WHERE foreign_id = :foreign_id
                   AND provider_name = :provider_name
                   AND model_name = :model_name
                   ', [
                    ':foreign_id'    => $place['id'],
                    ':provider_name' => self::PROVIDER_NAME,
                    ':model_name'    => self::PROVIDER_MODEL_PLACES
                ])->queryOne();
                if (!empty($temp)) {

                    $transaction->commit();
                    continue;
                }
                $photo='';
                if(!empty($place['photo'])) {
                    $photo = $this->saveFile($place['photo'], 'public_places');
                }


                Yii::$app->db->createCommand()->insert('{{%public_places}}', [
                    'name'        => $place['name'],
                    'description' => $this->clearText($place['description']),
                    'photo'       => $photo,
                    'icon'        => '',
                ])->execute();

                $placeId = Yii::$app->db->getLastInsertID();

                Yii::$app->db->createCommand()->insert('provider_combinations', [
                    'foreign_id'    => $place['id'],
                    'internal_id'   => $placeId,
                    'model_name'    => self::PROVIDER_MODEL_PLACES,
                    'provider_name' => self::PROVIDER_NAME,
                ])->execute();

                $transaction->commit();
            } catch (\Throwable $e) {

                echo 'Error ' . $e->getMessage() . PHP_EOL;
                $transaction->rollBack();
            }
        }
    }

}