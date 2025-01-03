<?php

namespace common\models;

use common\models\cruise\Cruise;
use Yii;

/**
 * This is the model class for table "ship".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $typeId Типкорабля
 * @property int $operatorId Судовладелец
 * @property int|null $stars
 * @property string|null $captain
 * @property string|null $cruiseDirector
 * @property string|null $cruiseDirectorTel
 * @property string|null $restaurantDirector
 * @property string|null $description
 * @property string|null $descriptionBig
 * @property string|null $discounts
 * @property int $status
 * @property int $priority
 * @property string|null $length
 * @property string|null $width
 * @property int|null $passengers
 * @property int|null $decksTotal Количество палуб
 * @property int|null $cabinsTotal Количество кают
 * @property string|null $additional Дополнительно на борту
 * @property string|null $currency Валюта на борту
 * @property string|null $video Видео о корабле
 * @property string|null $3dtour 3D тур по караблю
 * @property string|null $scheme Схема
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Cabin[] $cabins
 * @property Cruise[] $cruises
 * @property Deck[] $decks
 * @property Operator $operator
 * @property ShipMedia[] $shipMedia
 * @property TypeShip $type
 */
class Ship extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ship';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'typeId', 'operatorId'], 'required'],
            [['typeId', 'operatorId', 'stars', 'status', 'priority', 'passengers', 'decksTotal', 'cabinsTotal'], 'default', 'value' => null],
            [['typeId', 'operatorId', 'stars', 'status', 'priority', 'passengers', 'decksTotal', 'cabinsTotal'], 'integer'],
            [['description', 'descriptionBig', 'discounts', 'additional'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'captain', 'cruiseDirector', 'cruiseDirectorTel', 'restaurantDirector', 'length', 'width', 'currency', 'video', '3dtour', 'scheme'], 'string', 'max' => 255],
            [['slug', 'status'], 'unique', 'targetAttribute' => ['slug', 'status']],
            [['slug'], 'unique'],
            [['operatorId'], 'exist', 'skipOnError' => true, 'targetClass' => Operator::class, 'targetAttribute' => ['operatorId' => 'id']],
            [['typeId'], 'exist', 'skipOnError' => true, 'targetClass' => TypeShip::class, 'targetAttribute' => ['typeId' => 'id']],
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
            'typeId' => 'Type ID',
            'operatorId' => 'Operator ID',
            'stars' => 'Stars',
            'captain' => 'Captain',
            'cruiseDirector' => 'Cruise Director',
            'cruiseDirectorTel' => 'Cruise Director Tel',
            'restaurantDirector' => 'Restaurant Director',
            'description' => 'Description',
            'descriptionBig' => 'Description Big',
            'discounts' => 'Discounts',
            'status' => 'Status',
            'priority' => 'Priority',
            'length' => 'Length',
            'width' => 'Width',
            'passengers' => 'Passengers',
            'decksTotal' => 'Decks Total',
            'cabinsTotal' => 'Cabins Total',
            'additional' => 'Additional',
            'currency' => 'Currency',
            'video' => 'Video',
            '3dtour' => '3dtour',
            'scheme' => 'Scheme',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Cabins]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCabins()
    {
        return $this->hasMany(Cabin::class, ['ship_id' => 'id']);
    }

    /**
     * Gets query for [[Cruises]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruises()
    {
        return $this->hasMany(Cruise::class, ['ship_id' => 'id']);
    }

    /**
     * Gets query for [[Decks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDecks()
    {
        return $this->hasMany(Deck::class, ['ship_id' => 'id']);
    }

    /**
     * Gets query for [[Operator]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOperator()
    {
        return $this->hasOne(Operator::class, ['id' => 'operatorId']);
    }

    /**
     * Gets query for [[ShipMedia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShipMedia()
    {
        return $this->hasMany(ShipMedia::class, ['ship_id' => 'id']);
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(TypeShip::class, ['id' => 'typeId']);
    }
}
