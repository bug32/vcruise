<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cruise".
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
 *
 * @property City $cityEnd
 * @property City $cityStart
 * @property CruisePopularRouteRelation[] $cruisePopularRouteRelations
 * @property CruiseRegionRelation[] $cruiseRegionRelations
 * @property CruiseRiverRelation[] $cruiseRiverRelations
 * @property CruiseSuggestionRelation[] $cruiseSuggestionRelations
 * @property PopularRoute[] $popularRoutes
 * @property Port $portEnd
 * @property Port $portStart
 * @property Region[] $regions
 * @property River[] $rivers
 * @property Ship $ship
 * @property Suggestion[] $suggestions
 */
class Cruise extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cruise';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'route', 'date_start', 'date_end', 'date_start_timestamp', 'date_end_timestamp', 'ship_id'], 'required'],
            [['route', 'description', 'include', 'additional', 'discounts'], 'string'],
            [['status', 'date_start_timestamp', 'date_end_timestamp', 'days', 'nights', 'min_price', 'max_price', 'free_cabins', 'ship_id', 'port_start_id', 'port_end_id', 'city_start_id', 'city_end_id'], 'default', 'value' => null],
            [['status', 'date_start_timestamp', 'date_end_timestamp', 'days', 'nights', 'min_price', 'max_price', 'free_cabins', 'ship_id', 'port_start_id', 'port_end_id', 'city_start_id', 'city_end_id'], 'integer'],
            [['date_start', 'date_end', 'cabins_json', 'timetable_json', 'created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'route_short', 'map', 'currency', 'dock_start'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['city_start_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_start_id' => 'id']],
            [['city_end_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_end_id' => 'id']],
            [['port_start_id'], 'exist', 'skipOnError' => true, 'targetClass' => Port::class, 'targetAttribute' => ['port_start_id' => 'id']],
            [['port_end_id'], 'exist', 'skipOnError' => true, 'targetClass' => Port::class, 'targetAttribute' => ['port_end_id' => 'id']],
            [['ship_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ship::class, 'targetAttribute' => ['ship_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'route' => 'Route',
            'route_short' => 'Route Short',
            'description' => 'Description',
            'include' => 'Include',
            'additional' => 'Additional',
            'discounts' => 'Discounts',
            'map' => 'Map',
            'status' => 'Status',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'date_start_timestamp' => 'Date Start Timestamp',
            'date_end_timestamp' => 'Date End Timestamp',
            'days' => 'Days',
            'nights' => 'Nights',
            'min_price' => 'Min Price',
            'max_price' => 'Max Price',
            'currency' => 'Currency',
            'free_cabins' => 'Free Cabins',
            'ship_id' => 'Ship ID',
            'port_start_id' => 'Port Start ID',
            'port_end_id' => 'Port End ID',
            'dock_start' => 'Dock Start',
            'city_start_id' => 'City Start ID',
            'city_end_id' => 'City End ID',
            'cabins_json' => 'Cabins Json',
            'timetable_json' => 'Timetable Json',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CityEnd]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCityEnd()
    {
        return $this->hasOne(City::class, ['id' => 'city_end_id']);
    }

    /**
     * Gets query for [[CityStart]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCityStart()
    {
        return $this->hasOne(City::class, ['id' => 'city_start_id']);
    }

    /**
     * Gets query for [[CruisePopularRouteRelations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruisePopularRouteRelations()
    {
        return $this->hasMany(CruisePopularRouteRelation::class, ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[CruiseRegionRelations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruiseRegionRelations()
    {
        return $this->hasMany(CruiseRegionRelation::class, ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[CruiseRiverRelations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruiseRiverRelations()
    {
        return $this->hasMany(CruiseRiverRelation::class, ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[CruiseSuggestionRelations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruiseSuggestionRelations()
    {
        return $this->hasMany(CruiseSuggestionRelation::class, ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[PopularRoutes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPopularRoutes()
    {
        return $this->hasMany(PopularRoute::class, ['id' => 'popular_route_id'])->viaTable('cruise_popular_route_relation', ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[PortEnd]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPortEnd()
    {
        return $this->hasOne(Port::class, ['id' => 'port_end_id']);
    }

    /**
     * Gets query for [[PortStart]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPortStart()
    {
        return $this->hasOne(Port::class, ['id' => 'port_start_id']);
    }

    /**
     * Gets query for [[Regions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this->hasMany(Region::class, ['id' => 'region_id'])->viaTable('cruise_region_relation', ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[Rivers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRivers()
    {
        return $this->hasMany(River::class, ['id' => 'river_id'])->viaTable('cruise_river_relation', ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[Ship]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShip()
    {
        return $this->hasOne(Ship::class, ['id' => 'ship_id']);
    }

    /**
     * Gets query for [[Suggestions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSuggestions()
    {
        return $this->hasMany(Suggestion::class, ['id' => 'suggestion_id'])->viaTable('cruise_suggestion_relation', ['cruise_id' => 'id']);
    }
}
