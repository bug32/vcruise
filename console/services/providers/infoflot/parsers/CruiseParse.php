<?php

namespace console\services\providers\infoflot\parsers;

use common\helpers\FormatDate;
use common\models\Cruises;
use console\models\Cruise;
use console\models\ProviderCombination;
use console\services\providers\infoflot\InfoflotAPI;
use Throwable;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class CruiseParse extends InfoflotAPI
{

    protected array $providerShipID = [];
    protected array $providerPortID = [];
    protected array $providerCityID = [];

    public function run(): void
    {
        set_time_limit(0);

        $nextPage = '/cruises';
        $page     = 1;

        while ($nextPage) {
            try {
                $cruises = $this->request('/cruises', ['page' => $page, 'limit' => 100]);
            } catch (Throwable $e) {
                echo ' Error GET cruises: ' . $e->getMessage() . PHP_EOL;
                print_r(['page' => $page, 'limit' => 20]);
                die();
            }
            $page++;
            $nextPage = $cruises['pagination']['pages']['next']['url'] ?? NULL;

            if (empty($cruises['data'])) {
                echo 'data empty!';
                continue;
            }

            foreach ($cruises['data'] as $item) {
                try {
                    $cruise = $this->request('/cruises/' . $item['id']);
                    //  $cabins = $this->request('/cruises/' . $item['id'] . '/cabins');

                    $check = ProviderCombination::findOne([
                        'provider_name' => self::PROVIDER_NAME,
                        'foreign_id'    => $item['id'],
                        'model_name'    => self::PROVIDER_MODEL_NAME_CRUISE]);

                    $title = $this->clearText($cruise['beautifulName']);

                    $type_id = $this->getTypeId($cruise);

                    $slug   = $this->getSlug($cruise);
                    $params = [
                        'name'        => $title,
                        'slug'        => $slug,
                        'route'       => $this->clearText($cruise['route'] ?? $cruise['routeShort']),
                        'route_short' => $this->clearText($cruise['routeShort']),
                        'description' => $this->clearText($cruise['description']),
                        'include'     => $this->clearText($cruise['include']),
                        'additional'  => $this->clearText($cruise['additional']),
                        'discounts'   => $this->clearText($cruise['discountsText']),
                        'map'         => $cruise['map'],
                        'status'      => Cruises::STATUS_ACTIVE,

                        'date_start'           => $cruise['dateStart'],
                        'date_end'             => $cruise['dateEnd'],
                        'date_start_timestamp' => $cruise['dateStartTimestamp'],
                        'date_end_timestamp'   => $cruise['dateEndTimestamp'],
                        'days'                 => $cruise['days'],
                        'nights'               => $cruise['nights'],

                        'min_price' => $cruise['min_price'],
                        'max_price' => $cruise['max_price'],
                        'currency'  => $cruise['currency'],

                        'free_cabins' => $cruise['freeCabins'],

                        'ship_id' => $this->getShipId($cruise['ship'] ?? []),
                        'parent_cruise' => $cruise['parentCruise'],

                        'port_start_id' => $this->getPortId($cruise['portStart'] ?? ''),
                        'port_end_id'   => $this->getPortId($cruise['portEnd'] ?? ''),
                        'city_start_id' => $this->getCityId($cruise['startCity'] ?? ''),
                        'city_end_id'   => $this->getCityId($cruise['endCity'] ?? ''),

                        'type_id' => $type_id,

                        'timetable_json' => $this->getTimetableJson($cruise['timetable'] ?? []),
                        'cabins_json'    => json_encode([], JSON_THROW_ON_ERROR) //$this->getCabinsJson($cabins)
                    ];

                    if ($check) {
                        \Yii::$app->db->createCommand()->update('{{%cruises}}', $params, ['id' => $check['internal_id']])->execute();
                        echo 'UP cruise ID :' . $cruise['id'] . PHP_EOL;
                        continue;
                    } else {
                        \Yii::$app->db->createCommand()->insert('{{%cruises}}', $params)->execute();
                        $cruiseNew = \Yii::$app->db->createCommand("SELECT id FROM cruises WHERE slug=:slug", ['slug' => $slug])->queryOne();

                        \Yii::$app->db->createCommand()->insert('provider_combinations', [
                            'provider_name' => self::PROVIDER_NAME,
                            'foreign_id'    => $cruise['id'],
                            'model_name'    => self::PROVIDER_MODEL_NAME_CRUISE,
                            'internal_id'   => $cruiseNew['id'],
                        ])->execute();

                        $this->includeRivers($cruise['rivers'], $cruiseNew['id']);
                        $this->includeRoutes($cruise['popularRoutes'], $cruiseNew['id']);
                        $this->includePhoto($cruise['photos'], $cruiseNew['id']);
                        $this->includeRegion($cruise['regions'], $cruiseNew['id']);

                    }

                    echo 'ADD cruise ID :' . $cruise['id'] . PHP_EOL;
                    echo "Internal ID :" . ($cruiseNew['id'] ?? $check['internal_id']) . PHP_EOL;
                } catch (Throwable $e) {
                    echo "Error " . $e->getMessage() . PHP_EOL;
                    continue;
                }
            }
        }
    }

    protected function getSlug(array $cruise): string
    {
        $name  = $this->clearText($cruise['beautifulName']);
        $route = $this->clearText($cruise['routeShort'] ?? $cruise['route']);
        $route = trim(preg_replace('/\s*\([^)]*\)/', '', $route));
        $date  = FormatDate::formatDate($cruise['dateStart'], 'd MMMM');

        $title = 'Круиз ';
        if (!empty($name)) {
            $title .= $name . ' из ' . $cruise['startCityName'] . ' с ' . $date;
        } else {
            $title .= ' из ' . $route . ' с ' . $date;
        }

        return Inflector::slug($title);
    }


    protected function getShipId(mixed $ship): int
    {
        if (empty($ship['id'])) {
            throw new \RuntimeException('Ship not found');
        }
        if (empty($this->providerPortID)) {
            $this->providerShipID = $this->getProviderShips();
        }

        if ($this->providerShipID[$ship['id']]) {
            return $this->providerShipID[$ship['id']]['internal_id'];
        }

        $providerCombination = ProviderCombination::findOne([
            'provider_name' => self::PROVIDER_NAME,
            'foreign_id'    => $ship['id'],
            'model_name'    => self::PROVIDER_MODEL_NAME_SHIP
        ]);
        if (!$providerCombination) {
            throw new \RuntimeException('Ship not found');
        }

        $this->providerShipID[$ship['id']] = $providerCombination->internal_id;

        return $providerCombination->internal_id;
    }

    protected function getPortId(mixed $param): int
    {
        if (empty($param['id'])) {
            return 1;
        }

        $this->providerPortID = $this->getProviderPort();

        if ($this->providerPortID[$param['id']]) {
            return $this->providerPortID[$param['id']]['internal_id'];
        }

        $providerCombination = ProviderCombination::findOne([
            'provider_name' => self::PROVIDER_NAME,
            'foreign_id'    => $param['id'],
            'model_name'    => self::PROVIDER_MODEL_NAME_PORT
        ]);
        if (!$providerCombination) {
            return 0;
        }

        $this->providerPortID[$param['id']] = $providerCombination->internal_id;

        return $providerCombination->internal_id;
    }

    protected function getCityId(mixed $param)
    {
        if (empty($param['id'])) {
            return 1;
        }

        if (empty($this->providerCityID)) {
            $this->providerCityID = $this->getProviderCity();
        }

        if ($this->providerCityID[$param['id']]) {
            return $this->providerCityID[$param['id']]['internal_id'];
        }

        $providerCombination = ProviderCombination::findOne([
            'provider_name' => self::PROVIDER_NAME,
            'foreign_id'    => $param['id'],
            'model_name'    => self::PROVIDER_MODEL_NAME_CITY
        ]);

        if (!$providerCombination) {
            return 1;
        }

        $this->providerCityID[$param['id']] = $providerCombination->internal_id;

        return $providerCombination->internal_id;
    }


    /**
     * @throws Exception
     * @throws \JsonException
     */
    protected function getTimetableJson(mixed $timetable): false|string
    {
        $out = [];
        if (empty($timetable)) {
            return json_encode($out, JSON_THROW_ON_ERROR);
        }

        foreach ($timetable as $item) {
            $item['cityId']      = $this->getCityId($item['cityId']);
            $item['port']        = $this->getPortId($item['port']);
            $item['description'] = $this->clearText($item['description']);

            if (!empty($item['excursions'])) {
                foreach ($item['excursions'] as $key => $excursion) {
                    unset(
                        $item['excursions'][$key]['included'],
                        $item['excursions'][$key]['hasTranslate'],
                        $item['excursions'][$key]['images'],
                    );
                }
            }

            unset(
                $item['id'],
                $item['cruiseId'],
                $item['city'],
                $item['hideDate'],
                $item['hideTime']
            );
            $out[] = $item;
        }

        return json_encode($out, JSON_THROW_ON_ERROR);
    }

    protected function getProviderShips(): array
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM provider_combinations 
         WHERE provider_name = :provider_name AND model_name = :model_name', [
            ':provider_name' => self::PROVIDER_NAME,
            ':model_name'    => self::PROVIDER_MODEL_NAME_SHIP
        ])->queryAll();

        if (empty($result)) {
            return [];
        }

        return ArrayHelper::index($result, 'foreign_id');
    }

    protected function getProviderPort(): array
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM provider_combinations 
         WHERE provider_name = :provider_name AND model_name = :model_name', [
            ':provider_name' => self::PROVIDER_NAME,
            ':model_name'    => self::PROVIDER_MODEL_NAME_PORT
        ])->queryAll();

        if (empty($result)) {
            return [];
        }

        return ArrayHelper::index($result, 'foreign_id');
    }

    protected function getProviderCity(): array
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM provider_combinations 
         WHERE provider_name = :provider_name AND model_name = :model_name', [
            ':provider_name' => self::PROVIDER_NAME,
            ':model_name'    => self::PROVIDER_MODEL_NAME_CITY
        ])->queryAll();

        if (empty($result)) {
            return [];
        }

        return ArrayHelper::index($result, 'foreign_id');
    }

    // Тип круиза

    /**
     * @throws Exception
     */
    protected function getTypeId($cruise): int
    {   // тип 1 - значение не выбрано.
        if (empty($cruise['type']['name'])) {
            return 1;
        }

        $typeName = trim($cruise['type']['name']);
        $result   = \Yii::$app->db->createCommand('SELECT * FROM cruise_types WHERE name="' . $typeName . '"')->queryOne();

        if (empty($result)) {
            \Yii::$app->db->createCommand()->insert('cruise_types', [
                'name' => $typeName,
                'slug' => Inflector::slug($typeName),
            ])->execute();

            $result = \Yii::$app->db->createCommand('SELECT * FROM cruise_types WHERE name="' . $typeName . '"')->queryOne();
        }

        return $result['id'];
    }

    /**
     * @throws Exception
     */
    protected function includeRivers($rivers, int $internal_id): void
    {
        if (empty($rivers)) {
            return;
        }

        foreach ($rivers as $river) {
            $temp = \Yii::$app->db->createCommand('SELECT id from rivers WHERE name=:name', [':name' => $river['name']])->queryOne();
            if (empty($temp)) {
                echo 'NOT RIVER ' . $river['name'] . PHP_EOL;
                continue;
            }

            \Yii::$app->db->createCommand()->insert('cruise_river_relations', [
                'cruise_id' => $internal_id,
                'river_id'  => $temp['id'],
            ])->execute();

        }

    }

    /**
     * @throws Exception
     */
    protected function includeRoutes(mixed $popularRoutes, mixed $id): void
    {
        if (empty($popularRoutes)) {
            return;
        }
        foreach ($popularRoutes as $route) {
            $temp = \Yii::$app->db->createCommand('SELECT internal_id from provider_combinations 
                   WHERE 
                       foreign_id=:foreign_id AND 
                       provider_name=:provider_name AND
                       model_name=:name',
                [
                    ':foreign_id'    => $route['id'],
                    ':provider_name' => self::PROVIDER_NAME,
                    ':name'          => self::PROVIDER_MODEL_POPULAR_ROUTES
                ])->queryOne();

            if (empty($temp)) {
                echo 'NOT ROUTE ' . $route['name'] . PHP_EOL;
                continue;
            }

            \Yii::$app->db->createCommand()->insert('cruise_popular_route_relations', [
                'cruise_id'        => $id,
                'popular_route_id' => $temp['internal_id'],
            ])->execute();
        }

    }

    protected function includePhoto(mixed $photos, mixed $id): void
    {
        if (empty($photos)) {
            return;
        }

        foreach ($photos as $photo) {

            if (empty($photo['filename'])) {
                continue;
            }
            $src = $this->saveFile($photo['filename'], 'cruise/' . $id . '/photos');

            \Yii::$app->db->createCommand()->insert('cruise_medias', [
                'cruise_id' => $id,
                'url'       => $src,
                'name'      => $photo['description'],
                'alt'       => $photo['description'],
                'mime_type' => $photo['filetype'],
                'size'      => $photo['filesize'],
                'priority'  => $photo['position'],
            ])->execute();

        }
    }

    protected function includeRegion(mixed $regions, mixed $id): void
    {
        if(empty($regions)){
            return;
        }

        foreach ($regions as $region) {

            $temp = \Yii::$app->db->createCommand('SELECT internal_id from provider_combinations 
                   WHERE 
                       foreign_id=:foreign_id AND 
                       provider_name=:provider_name AND
                       model_name=:name',
                [
                    ':foreign_id'    => $region['id'],
                    ':provider_name' => self::PROVIDER_NAME,
                    ':name'          => self::PROVIDER_MODEL_REGIONS
                ])->queryOne();

            if( empty($temp)){
                continue;
            }

            \Yii::$app->db->createCommand()->insert('cruise_region_relations', [
                'cruise_id' => $id,
                'region_id' => $temp['internal_id'],
            ])->execute();

        }
    }
}