<?php

namespace console\services\providers\infoflot\parsers;

use common\models\ShipMedia;
use console\models\Cabin;
use console\models\Deck;
use console\models\Ship;
use console\models\Suggestion;
use console\services\providers\infoflot\InfoflotAPI;
use Random\RandomException;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\helpers\Inflector;

class ShipParser extends InfoflotAPI
{
    protected \common\models\Ship $model;

    /**
     * @var string Название таблицы для связи id провайдера и id модели в нутри системы
     */
    protected string $tableRelation = 'provider_ship';

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
     */
    public function run(): bool
    {

        set_time_limit(0);

        $nextPage = '/ships?key=' . self::TOKEN;

        while ($nextPage) {
            $list = $this->request($nextPage);

            $nextPage = !empty($list['pagination']['pages']['next']['url']) ? (self::BASE_URL . $list['pagination']['pages']['next']['url']) : NULL;

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
print_r($ship); die();
                try {
                    // Берем внутренний id из таблицы поставщиков информации о кораблях
                    // если информации нет, то корабль новый
                    $internalId = $this->getInternalId($item['id']);
                } catch (Exception $e) {
                    $internalId = Ship::createEmpty();
                    $isNewShip  = TRUE;
                }

                try {
                    $cabinsTotal = !empty($ship['cabins']) ? count($ship['cabins']) : 0;
                    $deckTotal   = !empty($ship['decks']) ? count($ship['decks']) : 0;

                    $params = [
                        'id'                 => $internalId,
                        'name'               => trim($ship['name']),
                        'slug'               => trim($ship['url']),
                        'typeId'             => 'Type ID',
                        'operatorId'         => 'Operator ID',
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
                        'scheme'             => $this->saveScheme($ship, 'ships/' . $internalId),

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
                        $this->savePhotos($ship, $internalId); // загрузка фото корабля
                    }

                    //$this->includeOnboard($ship);          //
                    $this->includeSug($ship);              //
                    $this->saveDeck($ship['decks'], $internalId);
                    $this->saveCabinType($ship['cabinTypes'], $internalId);
                    $this->saveCabins($ship['cabins'], $internalId);

                    echo "Create ShipID " . $ship['id'] . PHP_EOL;
                } catch (Exception $e) {
                    Ship::deleteAll(['id' => $internalId]);
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
            'SELECT internal_id FROM ' . $this->tableRelation . ' WHERE provider_name = :provider_name AND foreign_id = :foreign_id',
            [
                ':provider_name' => self::PROVIDER_NAME,
                ':foreign_id'    => $id
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
            return $this->saveFile($file['svgScheme']['url'], $shipID . '/scheme');
        }

        if (!empty($file['files']['scheme']['path'])) {
            return $this->saveFile($file['files']['scheme']['path'], $shipID . '/scheme');
        }

        return '';
    }

    /**
     * @throws Exception
     */
    protected function savePhotos($ship, $internalId): void
    {
        if (empty($ship['photos'])) {
            error_log('' . $internalId . ' - нет фото');
            return;
        }

        foreach ($ship['photos'] as $photo) {

            $file = pathinfo($photo['filename']);

            $photoPath = $this->saveFile($photo['filename'], 'ships/' . $internalId . '/gallery');
            if (!$photoPath) {
                continue;
            }

            $params = [
                'alt'      => trim($photo['description']),
                'path'     => $photoPath,
                'position' => $photo['position'],
                'type'     => $photo['filetype'],
                'size'     => $photo['filesize'],
                'ship_id'  => $internalId,
            ];

            Yii::$app->db->createCommand()->insert('ship_photo', $params)->execute();
        }
        return;
    }


    /**
     * @throws Exception
     */
    public function includeSug($item, $shipId): void
    {
        if (empty($item['sug'])) {
            return;
        }

        foreach ($item['sug'] as $sug) {
            if (empty($this->suggests[$sug['type_name']])) {
                $params = [
                    'name'        => $sug['title'],
                    'label'       => $sug['label'],
                    'slug'        => Inflector::slug($sug['title']),
                    'icon'        => $this->saveFile($sug['icon'], 'suggest'),
                    'description' => strip_tags($sug['descr']),
                ];

                Yii::$app->db->createCommand()->insert('suggestion', $params)->execute();
                $this->suggests = $this->getSuggests();
            }

            $params = [
                'suggest_id' => $this->suggests[$sug['type_name']],
                'ship_id'    => $shipId,
                'priority'   => $sug['type_priority'],
            ];
            Yii::$app->db->createCommand()->insert('suggestion_ship_relation', $params)->execute();
        }
    }

    protected function getSuggests(): array
    {
        return Suggestion::find()->select('id')->indexBy('name')->asArray()->column();
    }

    protected function saveDeck(mixed $decks, int|string|null $shipId): void
    {
        $providerDeck = $this->getProviderDeck($shipId);
        foreach ($decks as $deck) {
            if (!empty($providerDeck[$deck['id']])) {
                continue;
            }

            $params = [
                'name'     => $deck['name'],
                'priority' => $deck['position'],
                'slug'     => Inflector::slug($deck['name']),
                'ship_id'  => $shipId,
                'status'   => 10,
            ];

            $model = new Deck();
            $model->setAttributes($params);
            if (!$model->save()) {
                continue;
            }

            $params = [
                'provider_name' => self::PROVIDER_NAME,
                'foreign_id'    => $deck['id'],
                'internal_id'   => $model->id,
            ];
            Yii::$app->db->createCommand()->insert('provider_deck', $params)->execute();
        }
    }

    protected function saveCabins(mixed $cabins, $shipId): void
    {
        $providerCabin = $this->getProviderCabin($shipId);
        foreach ($cabins as $cabin) {
            if (!empty($providerCabin[$cabin['id']])) {
                continue;
            }

            $params = [
                'name'             => $cabin['name'],
                'deck_id'          => $cabin['deck_id'],
                'ship_id'          => $shipId,
                'cabin_type_id'    => '',
                'places'           => $cabin['places']['main'] ?? 0,
                'additionalPlaces' => $cabin['places']['additional'] ?? 0,
            ];

            $model = new Cabin();
            $model->setAttributes($params);
            if (!$model->save()) {
                continue;
            }

            $params = [
                'provider_name' => self::PROVIDER_NAME,
                'foreign_id'    => $cabin['id'],
                'internal_id'   => $model->id,
            ];
            Yii::$app->db->createCommand()->insert('provider_cabin', $params)->execute();
        }
    }

    protected function saveCabinType(mixed $cabinTypes, int|string|null $internalId): void
    {
        $providerCabinType = $this->getProviderCabinType();
        foreach ($cabinTypes as $cabinType) {
            if (empty($providerCabinType[$cabinType['id']])) {
                $params = [
                    'provider_name' => self::PROVIDER_NAME,
                    'foreign_id'    => $cabinType['id'],
                    'internal_id'   => $internalId,
                ];
                Yii::$app->db->createCommand()->insert('provider_cabin_type', $params)->execute();

                $providerCabinType = $this->getProviderCabinType();
            }

            $params = [
                'name'        => $cabinType['name'],
                'ship_id'     => $internalId,
                'description' => $cabinType['description'],
                'priority'    => $cabinType['position'],
                'isEco'       => $cabinType['isEko'],
            ];
            Yii::$app->db->createCommand()->insert('cabin_type', $params)->execute();
            $id = Yii::$app->db->getLastInsertID();

            // Сожранение картинок кают
            foreach ($cabinType['photos'] as $photo) {
                $params = [
                    'cabin_type_id' => $id,
                    'url'           => $this->saveFile($photo['url'], 'ship/' . $internalId . '/cabin-type')
                ];

                Yii::$app->db->createCommand()->insert('cabin_type_photo', $params)->execute();
            }

            // Сохранение услуг
            // 'inRoomServices' -> onboard-services
            $services = explode(',', $cabinType['inRoomServices']);
            foreach ($services as $service) {
                $params = [
                    'cabin_type_id' => $id,
                    'service_id'    => $service,
                ];
                Yii::$app->db->createCommand()->insert('cabin_type_service', $params)->execute();
            }
        }
    }


}