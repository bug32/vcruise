<?php

namespace console\services\providers\infoflot\parsers;

use console\models\Cabin;
use console\models\CabinType;
use console\models\Deck;
use console\models\ProviderCombination;
use console\models\Ship;
use console\models\Suggestion;
use console\services\providers\infoflot\InfoflotAPI;
use Random\RandomException;
use Yii;
use yii\db\Exception;
use yii\helpers\Inflector;

class ShipParser extends InfoflotAPI
{
    protected \common\models\Ship $model;

    /**
     * @var string Название таблицы для связи id провайдера и id модели в нутри системы
     */

    protected array $suggests = [];

    public function __construct()
    {
        parent::__construct();

        $this->model    = new \common\models\Ship();
        $this->suggests = $this->getSuggests();
    }

    /**
     * @throws \JsonException
     * @throws RandomException
     * @throws Exception
     */
    public function run($updatePhoto = false): bool
    {

        set_time_limit(0);

        $nextPage = '/ships';
        $page     = 33;

        while ($nextPage) {
            $list = $this->request('/ships', ['limit' => 3, 'page' => $page]);
            $page++;

            $nextPage = $list['pagination']['pages']['next']['url'] ?? NULL;

            if (empty($list['data'])) {
                echo 'data empty!';
                continue;
            }

            $isNewShip = FALSE;
            foreach ($list['data'] as $item) {
                if (empty($item['id'])) {
                    continue;
                }

                $ship = $this->request('/ships/' . $item['id']);

                try {
                    // Берем внутренний id из таблицы поставщиков информации о кораблях
                    // если информации нет, то корабль новый
                    $internalId = $this->getInternalId($item['id']);
                    if (!$internalId) {
                        $internalId = Ship::createEmpty();
                        $isNewShip  = TRUE;
                        echo 'new ship: ' . $item['id'] . PHP_EOL;
                    }
                } catch (Exception $e) {
                    echo $e->getMessage() . PHP_EOL;
                    continue;
                }


                try {
                    $cabinsTotal = !empty($ship['cabins']) ? count($ship['cabins']) : 0;
                    $deckTotal   = !empty($ship['decks']) ? count($ship['decks']) : 0;

                    $operatorId = $this->getOperatorId($ship);
                    $shipTypeId = $this->getShipTypeId($ship);

                    $params = [
                        'id'                 => $internalId,
                        'name'               => trim($ship['name']),
                        'slug'               => trim($ship['url']),
                        'typeId'             => $shipTypeId,
                        'operatorId'         => $operatorId,
                        'stars'              => $ship['stars'] ?? 0,
                        'captain'            => trim($ship['captain']),
                        'cruiseDirector'     => trim($ship['criuseDirector']),
                        'cruiseDirectorTel'  => trim($ship['cruiseDirectorTel']),
                        'restaurantDirector' => trim($ship['restaurantDirector']),
                        'description'        => $this->clearText($ship['description']),
                        'descriptionBig'     => $this->clearText($ship['descriptionBig']),
                        'discounts'          => $this->getDiscount($ship['discounts']),
                        'include'            => $this->clearText($ship['include']),
                        'status'             => 10, // 10 - активен, 0 - не активен
                        'priority'           => 0,
                        'length'             => $ship['techSpecifications']['length'] ?? 0,
                        'width'              => $ship['techSpecifications']['width'] ?? 0,
                        'passengers'         => $ship['techSpecifications']['passengers'] ?? 0,
                        'decksTotal'         => $deckTotal,
                        'cabinsTotal'        => $cabinsTotal,
                        'additional'         => $this->clearText($ship['additional']),
                        'currency'           => 1, // 1 - рубли, 2 - доллары, 3 - евро
                        'video'              => $ship['video'],
                        '3dtour'             => $ship['3dtour'],
                        'scheme'             => $this->saveScheme($ship, $internalId),

                        'year'           => '',
                        'yearRenovation' => '',
                    ];

                    /*
                    if (!empty($ship['files']['photo']['path'])) {
                        $params['image_preview'] = $this->saveImage($ship['files']['photo'], 'ships/' . $internalId . '/preview');
                    }

                    if (!empty($ship['files']['captainPhoto']['path'])) {
                        $params['captainPhoto'] = $this->saveImage($ship['files']['captainPhoto'], 'ships/' . $internalId . '/captain');
                    }
                    */

                    Yii::$app->db->createCommand()->update(Ship::tableName(), $params, ['id' => $internalId])->execute();

                    if ($isNewShip) {
                        Yii::$app->db->createCommand()->insert('provider_combination',
                            [
                                'provider_name' => self::PROVIDER_NAME,
                                'foreign_id'    => $item['id'],
                                'internal_id'   => $internalId,
                                'model_name'    => self::PROVIDER_MODEL_NAME_SHIP
                            ])->execute();
                        $this->saveGallery($ship, $internalId); // загрузка фото корабля
                        $this->savePhoto($ship, $internalId);
                    }

                    if( $updatePhoto){
                        $this->saveGallery($ship, $internalId); // загрузка фото корабля
                        $this->savePhoto($ship, $internalId);
                    }

                    //$this->includeOnboard($ship);          //
                    $this->includeSug($ship, $internalId);              //
                    $this->saveDeck($ship, $internalId);
                    $this->saveCabinType($ship['cabinTypes'], $internalId);
                    $this->saveCabins($ship['cabins'], $internalId);

                    echo "Create ShipID " . $ship['id'] . PHP_EOL;
                } catch (Exception $e) {
                    Ship::deleteId($internalId);
                    ProviderCombination::deleteAll([
                        'provider_name' => self::PROVIDER_NAME,
                        'foreign_id'    => $item['id'],
                        'model_name'    => self::PROVIDER_MODEL_NAME_SHIP
                    ]);
                    echo("ShipID {$item['id']} Error insert" . $e->getMessage());
                    die();
                }


            }

        }


        return TRUE;
    }


    /*----------------------------*/
    protected function isShipActive($id): bool
    {
        $ship = $this->request('/ships-active/' . $id);

        return !empty($ship['id']);
    }

    /**
     * @throws Exception
     */
    protected function getInternalId($id): ?string
    {
        $result = Yii::$app->db->createCommand(
            'SELECT internal_id FROM provider_combination 
            WHERE provider_name = :provider_name AND foreign_id = :foreign_id AND model_name = :model_name',
            [
                ':provider_name' => self::PROVIDER_NAME,
                ':foreign_id'    => $id,
                ':model_name'    => self::PROVIDER_MODEL_NAME_SHIP,
            ]
        )->queryOne();

        if ($result) {
            return $result['internal_id'];
        }

        return NULL;
    }

    protected function getDiscount(string $discounts): string
    {

        $text = trim($discounts);
        if (empty($text)) {
            return '';
        }

        // TODO Парсим текст и загружаем картинки из текста. Заменяет ссылки на новые.

        return $text;
    }

    protected function saveScheme($file, $shipID): string
    {
        if (!empty($file['svgScheme']['url'])) {
            return $this->saveFile($file['svgScheme']['url'], 'ships/' . $shipID . '/scheme');
        }

        if (!empty($file['files']['scheme']['path'])) {
            return $this->saveFile($file['files']['scheme']['path'], 'ships/' . $shipID . '/scheme');
        }

        return '';
    }

    /**
     * @throws Exception
     */
    protected function saveGallery($ship, $internalId): void
    {
        if (empty($ship['photos'])) {
            error_log('' . $internalId . ' - нет фото');
            return;
        }

        foreach ($ship['photos'] as $photo) {

            //   $file = pathinfo($photo['filename']);

            $photoPath = $this->saveFile($photo['filename'], 'ships/' . $internalId . '/gallery');
            if (!$photoPath) {
                continue;
            }

            $params = [
                'alt'       => trim($photo['description']) ?? trim($ship['name']),
                'name'      => trim($photo['description']) ?? trim($ship['name']),
                'url'       => $photoPath,
                'key'       => 'gallery', // Фото галерея
                'priority'  => $photo['position'],
                'mime_type' => $photo['filetype'],
                'size'      => $photo['filesize'],
                'ship_id'   => $internalId,
            ];

            Yii::$app->db->createCommand()->insert('ship_media', $params)->execute();
        }
        return;
    }


    /**
     * @throws Exception
     */
    public function includeSug($ship, $shipId): void
    {
        if (empty($ship['sug'])) {
            return;
        }
        $this->suggests = $this->getSuggests();
        foreach ($ship['sug'] as $sug) {
            $title = $this->clearText($sug['title']);
            if (empty($this->suggests[$title])) {
                $params = [
                    'name'        => $title,
                    'label'       => trim($sug['label']),
                    'slug'        => Inflector::slug($title),
                    'icon'        => $this->saveFile($sug['icon'], 'suggest'),
                    'description' => $this->clearText(strip_tags($sug['descr'])),
                ];

                Yii::$app->db->createCommand()->insert('suggestion', $params)->execute();
                $this->suggests = $this->getSuggests();
            }

            $suggest_id = $this->suggests[$title];

            $temp = Yii::$app->db->createCommand(
                'SELECT * FROM suggestion_ship_relation WHERE suggestion_id = :suggestion_id AND ship_id = :ship_id',
                [
                    ':suggestion_id' => $suggest_id,
                    ':ship_id'       => $shipId
                ]
            )->queryOne();
            if ($temp) {
                continue;
            }

            $params = [
                'suggestion_id' => $suggest_id,
                'ship_id'       => $shipId,
                'priority'      => $sug['type_priority'],
            ];
            Yii::$app->db->createCommand()->insert('suggestion_ship_relation', $params)->execute();
        }
    }

    protected function getSuggests(): array
    {
        return Suggestion::find()->select('id')->indexBy('name')->asArray()->column();
    }

    protected function saveDeck(mixed $ship, int|string|null $shipId): void
    {
        if (empty($ship['decks'])) {
            return;
        }
        $decks = $ship['decks'];

        $providerDeck = $this->getProviderDeck();
        foreach ($decks as $deck) {
            if (!empty($providerDeck[$deck['id']])) {
                continue;
            }

            $deckName = trim($deck['name']) ;
            if(empty($deckName)){
                $deckName = 'Палуба ' . $deck['position'];
            }
            $params   = [
                'name'     => $deckName,
                'priority' => $deck['position'],
                'ship_id'  => $shipId,
                'status'   => 10,
            ];

            $model = new Deck();
            $model->setAttributes($params);
            if (!$model->save()) {
                echo 'Deck Error '.$deckName;
                print_r($deck);
                print_r($model->getErrors());
                print_r($model->getAttributes());
                die();
                continue;
            }
            echo "Add deckID: " . $model->id . PHP_EOL;


            $this->setProviderDeck($deck['id'], $model->id);
            $providerDeck[$deck['id']] = $model->id;

        }
    }

    protected function saveCabins(mixed $cabins, $shipId): void
    {
        $providerCabin     = $this->getProviderCabin($shipId);
        $providerCabinType = $this->getProviderCabinType($shipId);
        $providerDeck      = $this->getProviderDeck();
        foreach ($cabins as $cabin) {
            if (!empty($providerCabin[$cabin['id']])) {
                continue;
            }

            $cabinName = trim($cabin['name']);
            if(empty($cabinName)){
                $cabinName = 'Кабина ' . $cabin['typeFriendlyName'];
            }

            $params = [
                'name'             => $cabinName,
                'deck_id'          => $providerDeck[$cabin['deckId']]['internal_id'],
                'ship_id'          => $shipId,
                'cabin_type_id'    => $providerCabinType[$cabin['typeId']]['internal_id'],
                'description'      => $cabin['cabinDescription'],
                'places'           => $cabin['places']['main'] ?? 0,
                'additionalPlaces' => $cabin['places']['additional'] ?? 0,
            ];

            $model = new Cabin();
            $model->setAttributes($params);
            if (!$model->save()) {
                print_r($cabin);
                print_r($providerDeck[$cabin['deckId']]);
                print_r($model->getErrors());
                die();
                continue;
            }

            $this->setProviderCabin($cabin['id'], $model->id);
            $providerCabin[$cabin['id']] = $model->id;
        }
    }

    protected function saveCabinType(mixed $cabinTypes, string $shipId): void
    {
        $providerCabinType = $this->getProviderCabinType($shipId);
        foreach ($cabinTypes as $cabinType) {
            if (empty($providerCabinType[$cabinType['id']])) {

                $params = [
                    'name'        => $cabinType['name'],
                    'ship_id'     => $shipId,
                    'description' => $cabinType['description'],
                    'priority'    => $cabinType['position'],
                    'isEco'       => $cabinType['isEko'],
                ];

                $model = new CabinType();
                $model->setAttributes($params);
                if (!$model->save()) {
                    print_r($model->getErrors());
                    continue;
                }

                $params = [
                    'provider_name' => self::PROVIDER_NAME,
                    'foreign_id'    => $cabinType['id'],
                    'internal_id'   => $model->id,
                    'model_name'    => self::PROVIDER_MODEL_NAME_CABIN_TYPE
                ];
                Yii::$app->db->createCommand()->insert('provider_combination', $params)->execute();

                $providerCabinType = $this->getProviderCabinType($shipId);
            } else {
                continue;
            }


            // Сожранение картинок кают
            foreach ($cabinType['photos'] as $photo) {
                try {
                    $params = [
                        'cabin_type_id' => $model->id,
                        'url'           => $this->saveFile($photo, 'ships/' . $shipId . '/cabin-type')
                    ];
                } catch (\Throwable $e) {
                    echo $e->getMessage() . PHP_EOL;
                    print_r($photo);
                    echo $shipId . PHP_EOL;
                    continue;
                }

                Yii::$app->db->createCommand()->insert('cabin_type_media', $params)->execute();
            }

            // Сохранение услуг
            // 'inRoomServices' -> onboard-services
            $services = explode(',', $cabinType['inRoomServices']);
            $services = array_filter($services);
            foreach ($services as $service) {
                try {
                    $params = [
                        'cabin_type_id' => $model->id,
                        'service_id'    => $service,
                    ];
                    Yii::$app->db->createCommand()->insert('cabin_type_service_relation', $params)->execute();
                } catch (\Throwable $e) {
                    echo $e->getMessage() . PHP_EOL;
                }
            }
        }
    }

    protected function getOperatorId(array $ship)
    {
        $operator = $this->clearText($ship['operatorName']);

        $temp = Yii::$app->db->createCommand('Select id from operator where name = :name',
            [
                ':name' => $operator
            ])->queryOne();

        if (!empty($temp['id'])) {
            return $temp['id'];
        }

        Yii::$app->db->createCommand()->insert('operator', [
            'name'   => $operator,
            'slug'   => Inflector::slug($operator),
            'status' => 10
        ])->execute();


        $temp = Yii::$app->db->createCommand('Select id from operator where name = :name',
            [
                ':name' => $operator
            ])->queryOne();

        return $temp['id'];
    }

    protected function getShipTypeId(array $ship)
    {
        $type = $this->clearText($ship['typeName']);

        $temp = Yii::$app->db->createCommand('Select id from type_ship where name = :name',
            [
                ':name' => $type
            ])->queryOne();

        if (!empty($temp['id'])) {
            return $temp['id'];
        }

        Yii::$app->db->createCommand()->insert('type_ship', [
            'name'   => $type,
            'slug'   => Inflector::slug($type),
            'status' => 10
        ])->execute();


        $temp = Yii::$app->db->createCommand('Select id from type_ship where name = :name',
            [
                ':name' => $type
            ])->queryOne();

        return $temp['id'];
    }

    protected function savePhoto(array $ship, int|string|null $internalId)
    {
        foreach ($ship['files'] as $key => $file) {
            if (empty($file['filename'])) {
                continue;
            }

            $photoPath = $this->saveFile($file['path'], 'ships/' . $internalId . '/file');

            $alt = '';
            switch ($key) {
                case  'photo':
                    $alt = $ship['name'];
                    break;
                case  'scheme':
                    $alt = 'Схема';
                    break;
                case  'captainPhoto':
                    $alt = $ship['captain'];
                    break;
                case 'cruiseDirectorPhoto':
                    $alt = $ship['criuseDirector'];
                    break;
                case 'restaurantDirectorPhoto':
                    $alt = $ship['restaurantDirector'];
                    break;
            }

            $params = [
                'alt'       => trim($alt),
                'name'      => trim($ship['name'].'-'.$alt),
                'url'       => $photoPath,
                'key'       => $key, // Фото галерея
                'priority'  => 0,
                'mime_type' => $file['type'],
                'size'      => $file['size'],
                'ship_id'   => $internalId,
            ];

            Yii::$app->db->createCommand()->insert('ship_media', $params)->execute();
            break;
        }
    }


}