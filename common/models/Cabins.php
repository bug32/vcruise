<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cabins".
 *
 * @property int $id
 * @property int $ship_id
 * @property int $deck_id
 * @property int $cabin_type_id
 * @property string $name
 * @property string|null $description
 * @property int $places
 * @property int $additionalPlaces
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CabinTypes $cabinType
 * @property Decks $deck
 * @property Ships $ship
 */
class Cabins extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cabins';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ship_id', 'deck_id', 'cabin_type_id', 'name'], 'required'],
            [['ship_id', 'deck_id', 'cabin_type_id', 'places', 'additionalPlaces'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['cabin_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CabinTypes::class, 'targetAttribute' => ['cabin_type_id' => 'id']],
            [['deck_id'], 'exist', 'skipOnError' => true, 'targetClass' => Decks::class, 'targetAttribute' => ['deck_id' => 'id']],
            [['ship_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ships::class, 'targetAttribute' => ['ship_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ship_id' => 'Ship ID',
            'deck_id' => 'Deck ID',
            'cabin_type_id' => 'Cabin Type ID',
            'name' => 'Name',
            'description' => 'Description',
            'places' => 'Places',
            'additionalPlaces' => 'Additional Places',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CabinType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCabinType(): \yii\db\ActiveQuery
    {
        return $this->hasOne(CabinTypes::class, ['id' => 'cabin_type_id']);
    }

    /**
     * Gets query for [[Deck]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeck(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Decks::class, ['id' => 'deck_id']);
    }

    /**
     * Gets query for [[Ship]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShip(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Ships::class, ['id' => 'ship_id']);
    }
}
