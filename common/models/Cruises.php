<?php

namespace common\models;

use common\models\relations\CruisePopularRouteRelations;
use common\models\relations\CruiseRegionRelations;
use common\models\relations\CruiseRiverRelations;
use common\models\relations\CruiseSuggestionRelations;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery as ActiveQueryAlias;

/**
 * This is the model class for table "cruises".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $route
 * @property string|null $route_short
 * @property string|null $description
 * @property string|null $include Включено
 * @property string|null $additional Дополнительно
 * @property string|null $discounts Скидки
 * @property string|null $map ссылка на карту маршрута
 * @property int|null $status
 * @property string $date_start
 * @property string $date_end
 * @property int $date_start_timestamp
 * @property int $date_end_timestamp
 * @property int|null $days
 * @property int|null $nights
 * @property int|null $min_price
 * @property int|null $max_price
 * @property string|null $currency Валюта
 * @property int|null $free_cabins
 * @property int $ship_id
 * @property int|null $port_start_id
 * @property int|null $port_end_id
 * @property string|null $dock_start
 * @property int $city_start_id
 * @property int $city_end_id
 * @property string|null $cabins_json Свободные каюты
 * @property string|null $timetable_json Расписание
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $type_id Тип круиза
 *
 * @property Cities $cityEnd
 * @property Cities $cityStart
 * @property CruisePopularRouteRelations[] $cruisePopularRouteRelations
 * @property CruiseRegionRelations[]     $cruiseRegionRelations
 * @property CruiseRiverRelations[]      $cruiseRiverRelations
 * @property CruiseSuggestionRelations[] $cruiseSuggestionRelations
 * @property PopularRoutes[]             $popularRoutes
 * @property Ports                       $portEnd
 * @property Ports                       $portStart
 * @property Regions[]                   $regions
 * @property Rivers[]                    $rivers
 * @property Ships                       $ship
 * @property Suggestions[]               $suggestions
 * @property CruiseTypes                 $type
 */
class Cruises extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'cruises';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug', 'route', 'date_start', 'date_end', 'date_start_timestamp', 'date_end_timestamp', 'ship_id'], 'required'],
            [['route', 'route_short', 'description', 'include', 'additional', 'discounts'], 'string'],
            [['status', 'date_start_timestamp', 'date_end_timestamp', 'days', 'nights', 'min_price', 'max_price', 'free_cabins', 'ship_id', 'port_start_id', 'port_end_id', 'city_start_id', 'city_end_id', 'type_id'], 'integer'],
            [['date_start', 'date_end', 'cabins_json', 'timetable_json', 'created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'map', 'currency', 'dock_start'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['city_end_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_end_id' => 'id']],
            [['city_start_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_start_id' => 'id']],
            [['port_end_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ports::class, 'targetAttribute' => ['port_end_id' => 'id']],
            [['port_start_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ports::class, 'targetAttribute' => ['port_start_id' => 'id']],
            [['ship_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ships::class, 'targetAttribute' => ['ship_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CruiseTypes::class, 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'route' => 'Route',
            'route_short' => 'Route Short',
            'description' => 'Description',
            'include' => 'Включено',
            'additional' => 'Дополнительно',
            'discounts' => 'Скидки',
            'map' => 'ссылка на карту маршрута',
            'status' => 'Status',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'date_start_timestamp' => 'Date Start Timestamp',
            'date_end_timestamp' => 'Date End Timestamp',
            'days' => 'Days',
            'nights' => 'Nights',
            'min_price' => 'Min Price',
            'max_price' => 'Max Price',
            'currency' => 'Валюта',
            'free_cabins' => 'Free Cabins',
            'ship_id' => 'Ship ID',
            'port_start_id' => 'Port Start ID',
            'port_end_id' => 'Port End ID',
            'dock_start' => 'Dock Start',
            'city_start_id' => 'City Start ID',
            'city_end_id' => 'City End ID',
            'cabins_json' => 'Свободные каюты',
            'timetable_json' => 'Расписание',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'type_id' => 'Тип круиза',
        ];
    }

    /**
     * Gets query for [[CityEnd]].
     *
     * @return ActiveQueryAlias
     */
    public function getCityEnd(): ActiveQueryAlias
    {
        return $this->hasOne(Cities::class, ['id' => 'city_end_id']);
    }

    /**
     * Gets query for [[CityStart]].
     *
     * @return ActiveQueryAlias
     */
    public function getCityStart(): ActiveQueryAlias
    {
        return $this->hasOne(Cities::class, ['id' => 'city_start_id']);
    }

    /**
     * Gets query for [[CruisePopularRouteRelations]].
     *
     * @return ActiveQueryAlias
     */
    public function getCruisePopularRouteRelations(): ActiveQueryAlias
    {
        return $this->hasMany(CruisePopularRouteRelations::class, ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[CruiseRegionRelations]].
     *
     * @return ActiveQueryAlias
     */
    public function getCruiseRegionRelations(): ActiveQueryAlias
    {
        return $this->hasMany(CruiseRegionRelations::class, ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[CruiseRiverRelations]].
     *
     * @return ActiveQueryAlias
     */
    public function getCruiseRiverRelations(): ActiveQueryAlias
    {
        return $this->hasMany(CruiseRiverRelations::class, ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[CruiseSuggestionRelations]].
     *
     * @return ActiveQueryAlias
     */
    public function getCruiseSuggestionRelations(): ActiveQueryAlias
    {
        return $this->hasMany(CruiseSuggestionRelations::class, ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[PopularRoutes]].
     *
     * @return ActiveQueryAlias
     * @throws InvalidConfigException
     */
    public function getPopularRoutes(): ActiveQueryAlias
    {
        return $this->hasMany(PopularRoutes::class, ['id' => 'popular_route_id'])->viaTable('cruise_popular_route_relations', ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[PortEnd]].
     *
     * @return ActiveQueryAlias
     */
    public function getPortEnd(): ActiveQueryAlias
    {
        return $this->hasOne(Ports::class, ['id' => 'port_end_id']);
    }

    /**
     * Gets query for [[PortStart]].
     *
     * @return ActiveQueryAlias
     */
    public function getPortStart(): ActiveQueryAlias
    {
        return $this->hasOne(Ports::class, ['id' => 'port_start_id']);
    }

    /**
     * Gets query for [[Regions]].
     *
     * @return ActiveQueryAlias
     * @throws InvalidConfigException
     */
    public function getRegions(): ActiveQueryAlias
    {
        return $this->hasMany(Regions::class, ['id' => 'region_id'])->viaTable('cruise_region_relations', ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[Rivers]].
     *
     * @return ActiveQueryAlias
     * @throws InvalidConfigException
     */
    public function getRivers(): ActiveQueryAlias
    {
        return $this->hasMany(Rivers::class, ['id' => 'river_id'])->viaTable('cruise_river_relations', ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[Ship]].
     *
     * @return ActiveQueryAlias
     */
    public function getShip()
    {
        return $this->hasOne(Ships::class, ['id' => 'ship_id']);
    }

    /**
     * Gets query for [[Suggestions]].
     *
     * @return ActiveQueryAlias
     * @throws InvalidConfigException
     */
    public function getSuggestions(): ActiveQueryAlias
    {
        return $this->hasMany(Suggestions::class, ['id' => 'suggestion_id'])->viaTable('cruise_suggestion_relations', ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[Type]].
     *
     * @return ActiveQueryAlias
     */
    public function getType(): ActiveQueryAlias
    {
        return $this->hasOne(CruiseTypes::class, ['id' => 'type_id']);
    }
}
