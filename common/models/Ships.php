<?php

namespace common\models;

use common\models\relations\SuggestionShipRelations;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "ships".
 *
 * @property int                       $id
 * @property string                    $name
 * @property string                    $slug
 * @property int|null                  $typeId         Тип корабля
 * @property int|null                  $operatorId     Судовладелец
 * @property int|null                  $stars
 * @property string|null               $captain
 * @property string|null               $cruiseDirector
 * @property string|null               $cruiseDirectorTel
 * @property string|null               $restaurantDirector
 * @property string|null               $description
 * @property string|null               $descriptionBig
 * @property string|null               $discounts
 * @property int|null                  $status
 * @property int|null                  $priority
 * @property string|null               $length
 * @property string|null               $width
 * @property int|null                  $passengers
 * @property int|null                  $decksTotal     Количество палуб
 * @property int|null                  $cabinsTotal    Количество кают
 * @property string|null               $additional     Дополнительно на борту
 * @property string|null               $include        Включено на борту
 * @property string|null               $currency       Валюта на борту
 * @property string|null               $video          Видео о корабле
 * @property string|null $3dtour 3D тур по кораблю
 * @property string|null               $scheme         Схема
 * @property int|null                  $year           Год выпуска
 * @property int|null                  $yearRenovation Год ремонта
 * @property string                    $created_at
 * @property string                    $updated_at
 *
 * @property CabinTypes[]              $cabinTypes
 * @property Cabins[]                  $cabins
 * @property Cruises[]                 $cruises
 * @property Decks[]                   $decks
 * @property Operators                 $operator
 * @property ShipMedias[]              $shipMedias
 * @property SuggestionShipRelations[] $suggestionShipRelations
 * @property Suggestions[]             $suggestions
 * @property ShipTypes                 $type
 */
class Ships extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'ships';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [
                [
                    'typeId', 'operatorId', 'stars', 'status', 'priority', 'passengers', 'decksTotal', 'cabinsTotal',
                    'year', 'yearRenovation'], 'integer'],
            [['description', 'descriptionBig', 'discounts', 'additional', 'include'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [
                [
                    'name', 'slug', 'captain', 'cruiseDirector', 'cruiseDirectorTel', 'restaurantDirector', 'length',
                    'width', 'currency', 'video', '3dtour', 'scheme'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['slug', 'status'], 'unique', 'targetAttribute' => ['slug', 'status']],
            [
                ['operatorId'], 'exist', 'skipOnError'     => TRUE, 'targetClass' => Operators::class,
                                         'targetAttribute' => ['operatorId' => 'id']],
            [
                ['typeId'], 'exist', 'skipOnError'     => TRUE, 'targetClass' => ShipTypes::class,
                                     'targetAttribute' => ['typeId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'                 => 'ID',
            'name'               => 'Name',
            'slug'               => 'Slug',
            'typeId'             => 'Тип корабля',
            'operatorId'         => 'Судовладелец',
            'stars'              => 'Stars',
            'captain'            => 'Captain',
            'cruiseDirector'     => 'Cruise Director',
            'cruiseDirectorTel'  => 'Cruise Director Tel',
            'restaurantDirector' => 'Restaurant Director',
            'description'        => 'Description',
            'descriptionBig'     => 'Description Big',
            'discounts'          => 'Discounts',
            'status'             => 'Status',
            'priority'           => 'Priority',
            'length'             => 'Length',
            'width'              => 'Width',
            'passengers'         => 'Passengers',
            'decksTotal'         => 'Количество палуб',
            'cabinsTotal'        => 'Количество кают',
            'additional'         => 'Дополнительно на борту',
            'include'            => 'Включено на борту',
            'currency'           => 'Валюта на борту',
            'video'              => 'Видео о корабле',
            '3dtour'             => '3D тур по кораблю',
            'scheme'             => 'Схема',
            'year'               => 'Год выпуска',
            'yearRenovation'     => 'Год ремонта',
            'created_at'         => 'Created At',
            'updated_at'         => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CabinTypes]].
     *
     * @return ActiveQuery
     */
    public function getCabinTypes(): ActiveQuery
    {
        return $this->hasMany(CabinTypes::class, ['ship_id' => 'id']);
    }

    /**
     * Gets query for [[Cabins]].
     *
     * @return ActiveQuery
     */
    public function getCabins(): ActiveQuery
    {
        return $this->hasMany(Cabins::class, ['ship_id' => 'id']);
    }

    /**
     * Gets query for [[Cruises]].
     *
     * @return ActiveQuery
     */
    public function getCruises(): ActiveQuery
    {
        return $this->hasMany(Cruises::class, ['ship_id' => 'id']);
    }

    /**
     * Gets query for [[Decks]].
     *
     * @return ActiveQuery
     */
    public function getDecks(): ActiveQuery
    {
        return $this->hasMany(Decks::class, ['ship_id' => 'id']);
    }

    /**
     * Gets query for [[Operator]].
     *
     * @return ActiveQuery
     */
    public function getOperator(): ActiveQuery
    {
        return $this->hasOne(Operators::class, ['id' => 'operatorId']);
    }

    /**
     * Gets query for [[ShipMedias]].
     *
     * @return ActiveQuery
     */
    public function getShipMedias(): ActiveQuery
    {
        return $this->hasMany(ShipMedias::class, ['ship_id' => 'id']);
    }

    public function getGallery(): ActiveQuery
    {
        return $this->hasMany(ShipMedias::class, ['ship_id' => 'id'])
            ->andWhere(['key' => ShipMedias::KEY_GALLERY]);
    }

    public function getCapitan(): ActiveQuery
    {
        return $this->hasOne(ShipMedias::class, ['ship_id' => 'id'])
            ->andWhere(['key' => ShipMedias::KEY_CAPITAN]);
    }

    /**
     * Основное фото корабля
     *
     * @return ActiveQuery
     */
    public function getPhoto(): ActiveQuery
    {
        return $this->hasOne(ShipMedias::class, ['ship_id' => 'id'])
            ->andWhere(['key' => ShipMedias::KEY_PHOTO]);
    }

    /**
     * Gets query for [[SuggestionShipRelations]].
     *
     * @return ActiveQuery
     */
    public function getSuggestionShipRelations(): ActiveQuery
    {
        return $this->hasMany(SuggestionShipRelations::class, ['ship_id' => 'id']);
    }

    /**
     * Gets query for [[Suggestions]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getSuggestions(): ActiveQuery
    {
        return $this->hasMany(Suggestions::class, ['id' => 'suggestion_id'])->viaTable('suggestion_ship_relations', ['ship_id' => 'id']);
    }

    /**
     * Gets query for [[Type]].
     *
     * @return ActiveQuery
     */
    public function getType(): ActiveQuery
    {
        return $this->hasOne(ShipTypes::class, ['id' => 'typeId']);
    }
}
