<?php

namespace console\services\providers\infoflot\parsers;

use console\models\City;
use console\services\providers\infoflot\InfoflotAPI;
use Yii;
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
                $model->country_id  = 0;
                if (!$model->save()) {
                    echo 'Error ID: ' . $city['id'] . PHP_EOL;
                    die();
                    continue;
                }

                \Yii::$app->db->createCommand()->insert('provider_combination', [
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
                    \Yii::$app->db->createCommand()->insert('city_media', $params)->execute();
                }

                if (!empty($cityDetail['photos'])) {


                    foreach ($cityDetail['photos'] as $photo) {
                        $params = [
                            'city_id' => $model->id,
                            'url'     => $this->saveFile($photo['filename'], 'cities/' . $model->id . '/gallery'),
                        ];
                        \Yii::$app->db->createCommand()->insert('city_media', $params)->execute();
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

                $countriesID = Yii::$app->db->createCommand('SELECT id FROM country WHERE slug = :slug', [
                    ':slug' => $slug,
                ])->queryOne();

                $params = [
                    'id'   => $country['id'],
                    'name' => $country['name'],
                    'slug' => $slug,
                ];

                if (empty($countriesID)) {
                    Yii::$app->db->createCommand()->insert('country', $params)->execute();
                    $countriesID = Yii::$app->db->createCommand('SELECT id FROM country WHERE slug = :slug', [
                        ':slug' => $slug,
                    ])->queryOne();
                }

                if (!empty($countriesID)) {
                    $countriesID = $countriesID['id'];
                } else {
                    throw new \RuntimeException('Country not found');
                }

                Yii::$app->db->createCommand()->insert('provider_combination', [
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
                $temp     = Yii::$app->db->createCommand('SELECT id FROM port WHERE slug = :slug', [
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

                Yii::$app->db->createCommand()->insert('port', [
                    'name'       => $port['name'],
                    'slug'       => $portSlug,
                    'city_id'    => $cityId,
                    'country_id' => $countryId,
                ])->execute();

                $portId = Yii::$app->db->getLastInsertID();
                Yii::$app->db->createCommand()->insert('provider_combination', [
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
                    Yii::$app->db->createCommand()->insert('dock', [
                        'port_id'     => $portId,
                        'name'        => $dock['name'],
                        'address'     => $dock['yandex_address'] ?? $dock['address'],
                        'coordinates' => $dock['yandex_coordinates'],
                    ])->execute();
                    $dockId = Yii::$app->db->getLastInsertID();

                    Yii::$app->db->createCommand()->insert('provider_combination', [
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

        $sql = "SELECT pc.internal_id, city.name 
                FROM city 
                LEFT JOIN provider_combination pc ON city.id = pc.internal_id
                WHERE  pc.model_name='city' AND pc.provider_name='infoflot'
                GROUP BY city.name, pc.internal_id";

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

        $sql = "SELECT pc.internal_id, country.name 
                FROM provider_combination pc
                LEFT JOIN country ON country.id = pc.internal_id
                WHERE pc.model_name='country' AND pc.provider_name='infoflot'
                GROUP BY country.name, pc.internal_id";

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

        $temp = Yii::$app->db->createCommand('SELECT id FROM country WHERE slug = :slug', [
            ':slug' => $slug,
        ])->queryOne();
        if ($temp) {
            return $temp['id'];
        }

        Yii::$app->db->createCommand()->insert('country', [
            'name' => $country,
            'slug' => $slug,
        ])->execute();

        $countryId = Yii::$app->db->createCommand('SELECT id FROM country WHERE slug= :slug', [
            ':slug' => $slug,
        ])->queryOne();
        return $countryId['id'];
    }

    protected function addCity(string $city, int $countryId)
    {
        $slug = Inflector::slug($city);

        $temp = Yii::$app->db->createCommand('SELECT id FROM city WHERE slug = :slug', [
            ':slug' => $slug,
        ])->queryOne();
        if ($temp) {
            return $temp['id'];
        }

        Yii::$app->db->createCommand()->insert('{{%city}}', [
            'name'       => $city,
            'slug'       => $slug,
            'country_id' => $countryId,
        ])->execute();

        $cityId = Yii::$app->db->createCommand('SELECT id FROM city WHERE slug=:slug', [
            'slug' => $slug,
        ])->queryOne();

        return $cityId['id'];
    }

}