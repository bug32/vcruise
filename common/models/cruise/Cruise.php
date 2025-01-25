<?php

namespace common\models\cruise;

use common\models\City;
use common\models\PopularRoute;
use common\models\Port;
use common\models\Region;
use common\models\River;
use common\models\Ship;
use common\models\Suggestion;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "cruise".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $route
 * @property string $route_short
 * @property string|null $description
 * @property string|null $include Включено
 * @property string|null $additional Дополнительно
 * @property string|null $discounts Скидки
 * @property string|null $map ссылка на карту маршрута
 * @property string $date_start
 * @property string $date_end
 * @property int $date_start_timestamp
 * @property int $date_end_timestamp
 * @property int $days
 * @property int $nights
 * @property int $min_price
 * @property int $max_price
 * @property string $currency
 * @property int $free_cabins
 * @property int $ship_id
 * @property int $port_start_id
 * @property int $port_end_id
 * @property string|null $dock_start
 * @property int $city_start_id
 * @property int $city_end_id
 * @property int $status
 * @property string|null $cabins_json Свободные каюты
 * @property string|null $timetable_json Расписание
 * @property string $created_at
 * @property string $updated_at
 *
 * @property City $cityEnd
 * @property City $cityStart

  @property CruisePopularRouteRelation[] $cruisePopularRouteRelations
  @property CruiseRegionRelation[] $cruiseRegionRelations
  @property CruiseRiverRelation[] $cruiseRiverRelations
  @property CruiseSuggestionRelation[] $cruiseSuggestionRelations

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
            [['name', 'slug', 'route', 'route_short', 'date_start', 'date_end', 'date_start_timestamp', 'date_end_timestamp', 'days', 'nights', 'min_price', 'max_price', 'ship_id'], 'required'],
            [['route', 'description', 'include', 'additional', 'discounts'], 'string'],
            [['date_start', 'date_end', 'cabins_json', 'timetable_json', 'created_at', 'updated_at'], 'safe'],
            [['date_start_timestamp', 'date_end_timestamp', 'days', 'nights', 'min_price', 'max_price', 'free_cabins', 'ship_id', 'port_start_id', 'port_end_id', 'city_start_id', 'city_end_id'], 'default', 'value' => null],
            [['date_start_timestamp', 'date_end_timestamp', 'days', 'nights', 'min_price', 'max_price', 'free_cabins', 'ship_id', 'port_start_id', 'port_end_id', 'city_start_id', 'city_end_id'], 'integer'],
            [['name', 'slug', 'route_short', 'map', 'currency', 'dock_start'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            ['status', 'default', 'value' => 10],
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
     * @return ActiveQuery
     */
    public function getCityEnd(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_end_id']);
    }

    /**
     * Gets query for [[CityStart]].
     *
     * @return ActiveQuery
     */
    public function getCityStart(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_start_id']);
    }

 /*
    public function getCruisePopularRouteRelations()
    {
        return $this->hasMany(CruisePopularRouteRelation::class, ['cruise_id' => 'id']);
    }


    public function getCruiseRegionRelations()
    {
        return $this->hasMany(CruiseRegionRelation::class, ['cruise_id' => 'id']);
    }


    public function getCruiseRiverRelations()
    {
        return $this->hasMany(CruiseRiverRelation::class, ['cruise_id' => 'id']);
    }


    public function getCruiseSuggestionRelations()
    {
        return $this->hasMany(CruiseSuggestionRelation::class, ['cruise_id' => 'id']);
    }
 */

    /**
     * Gets query for [[PopularRoutes]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getPopularRoutes(): ActiveQuery
    {
        return $this->hasMany(PopularRoute::class, ['id' => 'popular_route_id'])->viaTable('cruise_popular_route_relation', ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[PortEnd]].
     *
     * @return ActiveQuery
     */
    public function getPortEnd(): ActiveQuery
    {
        return $this->hasOne(Port::class, ['id' => 'port_end_id']);
    }

    /**
     * Gets query for [[PortStart]].
     *
     * @return ActiveQuery
     */
    public function getPortStart(): ActiveQuery
    {
        return $this->hasOne(Port::class, ['id' => 'port_start_id']);
    }

    /**
     * Gets query for [[Regions]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getRegions(): ActiveQuery
    {
        return $this->hasMany(Region::class, ['id' => 'region_id'])->viaTable('cruise_region_relation', ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[Rivers]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getRivers(): ActiveQuery
    {
        return $this->hasMany(River::class, ['id' => 'river_id'])->viaTable('cruise_river_relation', ['cruise_id' => 'id']);
    }

    /**
     * Gets query for [[Ship]].
     *
     * @return ActiveQuery
     */
    public function getShip(): ActiveQuery
    {
        return $this->hasOne(Ship::class, ['id' => 'ship_id']);
    }

    /**
     * Gets query for [[Suggestions]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getSuggestions(): ActiveQuery
    {
        return $this->hasMany(Suggestion::class, ['id' => 'suggestion_id'])->viaTable('cruise_suggestion_relation', ['cruise_id' => 'id']);
    }
}
