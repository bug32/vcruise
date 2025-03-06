<?php

namespace console\services\providers\infoflot\parsers;

use common\models\Cruises;
use console\services\providers\infoflot\InfoflotAPI;
use yii\db\Exception;

class CabinsParser extends InfoflotAPI
{

    public $providerDecks = [];

    public $providercabinTypes = [];

    public $providerCruise = [];

    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->providerDecks      = $this->getProviderDeck();
        $this->providercabinTypes = $this->getProviderCabinType();

    }

    /**
     * @throws Exception
     * @throws \JsonException
     */
    public function run(): void
    {
        set_time_limit(0);
        // 0 - доступно, 1 - зарезервировано, 2 - продано, 4 - по запросу
        $status_human = [0 => "available", 1 => "забронировано", 2 => "sold", 4 => "ondemand"];


        $cruises = \Yii::$app->db->createCommand('SELECT id FROM cruises WHERE status=:status AND date_start_timestamp >= :date_start_timestamp', [
            ':status'               => Cruises::STATUS_ACTIVE,
            ':date_start_timestamp' => time(),
        ])->queryColumn();

        foreach ($cruises as $cruise) {
            try {
                $cruise_id  = $this->getCruiseId($cruise);
                $dataCabins = $this->request('/cruises/' . $cruise_id . '/cabins');

                if (empty($dataCabins)) {
                    continue;
                }

                if (!empty($dataCabins['status'])) {

                    echo 'Update cruise ' . $cruise . ' status inactive ' . PHP_EOL;
                    $this->updateCruise($cruise, ['status' => Cruises::STATUS_INACTIVE]);

                    continue;
                }

                $prices          = [];
                $defaultPrice    = [];
                $defaultPriceMin = 0;
                foreach ($dataCabins['prices'] as $type => $price) {
                    $prices[$type]       = $price['prices']['main_bottom']['adult'];
                    $defaultPrice[$type] = $price['prices']['default']??0;
                    $defaultPriceMin     = $defaultPrice[$type];
                }

                $statusCabins = [];
                foreach ($dataCabins['cabins'] as $cid => $cabin) {

                    if (empty($statusCabins[$cabin['deck_id']]['free'])) {
                        $statusCabins[$cabin['deck_id']]['free'] = 0;
                    }

                    $deck_id                        = $this->providerDecks[$cabin['deck_id']]['internal_id']??null;
                    $type_id                        = $this->providercabinTypes[$cabin['type_id']]['internal_id']??null;
                    if( $deck_id === null || $type_id === null ){ continue; }

                    $statusCabins[$deck_id]['name'] = $cabin['deck'];

                    if (empty($statusCabins[$deck_id]['total'])) {
                        $statusCabins[$deck_id]['total'] = 0;
                    } else {
                        $statusCabins[$deck_id]['total'] += 1;
                    }

                    $statusCabins[$deck_id]['by_type'][$type_id]['cabins'][$cid]['uid']      = $cid;
                    $statusCabins[$deck_id]['by_type'][$type_id]['cabins'][$cid]['name']     = $cabin['name'];
                    $statusCabins[$deck_id]['by_type'][$type_id]['cabins'][$cid]['price']    = $prices[$cabin['type_id']];
                    $statusCabins[$deck_id]['by_type'][$type_id]['cabins'][$cid]['status']   = $cabin['status'];
                    $statusCabins[$deck_id]['by_type'][$type_id]['cabins'][$cid]['status_h'] = $status_human[$cabin['status']];
                    $statusCabins[$deck_id]['by_type'][$type_id]['cabins'][$cid]['separate'] = $cabin['separate'];
                    $statusCabins[$deck_id]['by_type'][$type_id]['cabins'][$cid]['places']   = count($cabin['places']);

                    if (empty($statusCabins[$deck_id]['min_price'])) {
                        $statusCabins[$deck_id]['min_price'] = $prices[$cabin['type_id']];
                    } elseif ($statusCabins[$deck_id]['min_price'] > $prices[$cabin['type_id']]) {
                        $statusCabins[$deck_id]['min_price'] = $prices[$cabin['type_id']];
                    }

                    if (empty($statusCabins[$deck_id]['by_type'][$type_id]['min_price'])) {
                        $statusCabins[$deck_id]['by_type'][$type_id]['min_price'] = $prices[$cabin['type_id']];
                    } elseif ($statusCabins[$deck_id]['by_type'][$type_id]['min_price'] > $prices[$cabin['type_id']]) {
                        $statusCabins[$deck_id]['by_type'][$type_id]['min_price'] = $prices[$cabin['type_id']];
                        // на всякий пожарный, чтобы от подставить
                        $statusCabins[$deck_id]['by_type'][$type_id]['has_over'] = 'Y';
                    }

                    // если можно купить раздельно
                    if ($cabin['separate'] == 1) {
                        $statusCabins[$deck_id]['by_type'][$type_id]['cabins'][$cid]['gender'] = $cabin['gender'] == 1 ? 'M' : 'F';

                        $i = 0;
                        $k = 0;
                        foreach ($cabin['places'] as $idx => $place) {
                            if ($place['type'] == 0) {

                                // если продано, то выходим
                                if ($place['status'] == 2)
                                    continue;
                                // свободно
                                if ($place['status'] == 0) {
                                    ++$i;
                                    // по запросу
                                } else {
                                    ++$k;
                                }
                            }
                        }
                        if ($i > 0) {
                            $statusCabins[$deck_id]['by_type'][$type_id]['cabins'][$cid]['places']['can_buy'] = $i;
                        }

                        if ($k > 0) {
                            $statusCabins[$deck_id]['by_type'][$type_id]['cabins'][$cid]['places']['ask_manager'] = $k;
                        }
                    } else if ($cabin['status'] == 0) {
                        if (empty($statusCabins[$deck_id]['free'])) {
                            $statusCabins[$deck_id]['free'] = 0;
                        } else {
                            $statusCabins[$deck_id]['free'] += 1;
                        }

                        if (empty($statusCabins[$deck_id]['by_type'][$type_id]['free'])) {
                            $statusCabins[$deck_id]['by_type'][$type_id]['free'] = 0;
                        } else {
                            $statusCabins[$deck_id]['by_type'][$type_id]['free'] += 1;
                        }

                        if ($defaultPriceMin > $defaultPrice[$cabin['type_id']]) {
                            $defaultPriceMin = $defaultPrice[$cabin['type_id']];
                        }
                    }
                }

                $params = [
                    'cabins_json'  => $statusCabins,
                    'free_cabins'  => $dataCabins['cruise'][0]['freeCabins'],
                    'min_price'    => $dataCabins['cruise'][0]['min_price_absolute'],
                    'max_price'    => $dataCabins['cruise'][0]['max_price_absolute'],
                    'defaultPrice' => $defaultPriceMin,
                ];


                $this->updateCruise($cruise, $params);
                echo 'Update cruise ' . $cruise . ' OK ' . PHP_EOL;
                //  return;
            } catch (Exception $e) {
                echo 'Update cruise ' . $cruise . ' error ' . $e->getMessage() . PHP_EOL;
                continue;
            }
        }

    }

    /**
     * @throws Exception
     */
    protected function updateCruise($cruise_id, array $params)
    {
        /// echo 'Update cruise ' . $cruise_id . PHP_EOL;
        \Yii::$app->db->createCommand()->update('cruises', $params, 'id=:id', [':id' => $cruise_id])->execute();
    }

    protected function getCruiseId(int $cruise): int
    {
        if (empty($this->providerCruise[$cruise])) {
            $temp                          = \Yii::$app->db->createCommand('
                    SELECT foreign_id 
                    from provider_combinations 
                  WHERE 
                      internal_id=:internal_id AND provider_name=:provider_name AND model_name=:name',
                [
                    ':internal_id'   => $cruise,
                    ':provider_name' => self::PROVIDER_NAME,
                    ':name'          => self::PROVIDER_MODEL_NAME_CRUISE
                ])->queryOne();
            $this->providerCruise[$cruise] = $temp['foreign_id'];
        }

        return $this->providerCruise[$cruise];
    }

}