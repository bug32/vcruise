<?php

namespace console\services\providers\infoflot\parsers;

use common\helpers\FormatDate;
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
            }catch (Throwable $e){
                echo' Error GET cruises: ' . $e->getMessage().PHP_EOL;
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

                $slug   = $this->getSlug($cruise);
                $params = [
                    'name'                 => $title,
                    'slug'                 => $slug,
                    'route'                => $this->clearText($cruise['route'] ?? $cruise['routeShort']),
                    'route_short'          => $this->clearText($cruise['routeShort']),
                    'date_start'           => $cruise['dateStart'],
                    'date_end'             => $cruise['dateEnd'],
                    'date_start_timestamp' => $cruise['dateStartTimestamp'],
                    'date_end_timestamp'   => $cruise['dateEndTimestamp'],
                    'days'                 => $cruise['days'],
                    'nights'               => $cruise['nights'],
                    'min_price'            => $cruise['min_price'],
                    'max_price'            => $cruise['max_price'],
                    'currency'             => $cruise['currency'],
                    'free_cabins'          => $cruise['freeCabins'],
                    'ship_id'              => $this->getShipId($cruise['ship'] ?? []),
                    'port_start_id'        => $this->getPortId($cruise['portStart'] ?? ''),
                    'port_end_id'          => $this->getPortId($cruise['portEnd'] ?? ''),
                    'city_start_id'        => $this->getCityId($cruise['startCity'] ?? ''),
                    'city_end_id'          => $this->getCityId($cruise['endCity'] ?? ''),
                    'map'                  => $cruise['map'],
                    'timetable_json'       => $this->getTimetableJson($cruise['timetable'] ?? []),
                    'cabins_json'          => json_encode([], JSON_THROW_ON_ERROR) //$this->getCabinsJson($cabins)
                ];

                if ($check) {
                    \Yii::$app->db->createCommand()->update('{{%cruise}}', $params, ['id' => $check['internal_id']])->execute();
                } else {
                    \Yii::$app->db->createCommand()->insert('{{%cruise}}', $params)->execute();
                    $cruiseNew = \Yii::$app->db->createCommand("SELECT id FROM cruise WHERE slug=:slug", ['slug' => $slug])->queryOne();

                    \Yii::$app->db->createCommand()->insert('provider_combination', [
                        'provider_name' => self::PROVIDER_NAME,
                        'foreign_id'    => $cruise['id'],
                        'model_name'    => self::PROVIDER_MODEL_NAME_CRUISE,
                        'internal_id'   => $cruiseNew['id'],
                    ])->execute();
                }

                echo 'ADD cruise ID :' . $cruise['id'] . PHP_EOL;
                echo "Internal ID :" . ($cruiseNew['id'] ?? $check['internal_id']) . PHP_EOL;

                } catch (Throwable $e) {
                    echo "Error ". $e->getMessage() . PHP_EOL;
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
            return 0;
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
            return 0;
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
            return 0;
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
        $result = \Yii::$app->db->createCommand('SELECT * FROM provider_combination 
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
        $result = \Yii::$app->db->createCommand('SELECT * FROM provider_combination 
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
        $result = \Yii::$app->db->createCommand('SELECT * FROM provider_combination 
         WHERE provider_name = :provider_name AND model_name = :model_name', [
            ':provider_name' => self::PROVIDER_NAME,
            ':model_name'    => self::PROVIDER_MODEL_NAME_CITY
        ])->queryAll();

        if (empty($result)) {
            return [];
        }

        return ArrayHelper::index($result, 'foreign_id');
    }
}