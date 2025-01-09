<?php

namespace console\services\providers\infoflot\parsers;

use common\helpers\FormatDate;
use console\models\Cruise;
use console\models\ProviderCombination;
use console\services\providers\infoflot\InfoflotAPI;
use Throwable;
use yii\db\Exception;
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
            $nextPage = $this->request($nextPage . '?page=' . $page);
            $page++;

            if (empty($nextPage['data'])) {
                echo 'data empty!';
                continue;
            }

            foreach ($nextPage['data'] as $item) {


                try {
                    $cruise = $this->request('/cruises/' . $item['id']);
                    $cabins = $this->request('/cruises/' . $item['id'] . '/cabins');

                    $check = ProviderCombination::findOne([
                        'provider_name' => self::PROVIDER_NAME, 'foreign_id' => $item['id'],
                        'model_name'    => self::PROVIDER_MODEL_NAME_CRUISE]);

                    $title = $this->clearText($cruise['beautifulName']);



                    $params = [
                        'id'                   => $item['id'],
                        'name'                 => $title,
                        'slug'                 => $this->getSlug($cruise),
                        'route'                => $this->clearText($cruise['routeShort'] ?? $cruise['route']),
                        'date_start'           => $cruise['dateStart'],
                        'date_end'             => $cruise['dateEnd'],
                        'date_start_timestamp' => $cruise['dateStartTimestamp'],
                        'date_end_timestamp'   => $cruise['dateEndTimestamp'],
                        'days'                 => $cruise['days'],
                        'nights'               => $cruise['nights'],
                        'min_price'            => $cruise['minPrice'],
                        'max_price'            => $cruise['maxPrice'],
                        'currency'             => $cruise['currency'],
                        'free_cabins'          => $cruise['freeCabins'],
                        'ship_id'              => $this->getShipId($cruise['ship'] ?? []),
                        'port_start_id'        => $this->getPortId($cruise['portStart'] ?? ''),
                        'port_end_id'          => $this->getPortId($cruise['portEnd'] ?? ''),
                        'start_city_id'        => $this->getCityId($cruise['startCity'] ?? ''),
                        'end_city_id'          => $this->getCityId($cruise['endCity'] ?? ''),
                        'map'                  => $cruise['map'],
                        'timetable_json'       => $this->getTimetableJson($cruise['timetable'] ?? []),
                        'cabins_json'          => $this->getCabinsJson($cabins)
                    ];
                } catch (Throwable $e) {
                    continue;
                }
            }
        }
    }

    protected function getInternalId(mixed $id)
    {
        $providerCombination = ProviderCombination::findOne([
            'provider_name' => self::PROVIDER_NAME,
            'foreign_id'    => $id,
            'model_name'    => self::PROVIDER_MODEL_NAME_CRUISE
        ]);
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
        if ($this->providerShipID[$ship['id']]) {
            return $this->providerShipID[$ship['id']];
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

        if ($this->providerPortID[$param['id']]) {
            return $this->providerPortID[$param['id']];
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

        if ($this->providerCityID[$param['id']]) {
            return $this->providerCityID[$param['id']];
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
        }

        return json_encode($out, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws \JsonException
     */
    protected function getCabinsJson(array $cabins): false|string
    {
        $out = [];

        if (empty($cabins['cabins'])) {
            return json_encode($out, JSON_THROW_ON_ERROR);
        }

        foreach ($cabins['cabins'] as $cabin) {

        }


        return json_encode($out, JSON_THROW_ON_ERROR);
    }

}