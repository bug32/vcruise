<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deck".
 *
 * @property int $id
 * @property int $ship_id
 * @property int $priority
 * @property string $name
 * @property string|null $description
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Cabin[] $cabins
 * @property Ship $ship
 */
class Deck extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%decks}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['ship_id', 'name'], 'required'],
            [['ship_id', 'priority', 'status'], 'default', 'value' => null],
            [['ship_id', 'priority', 'status'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['ship_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ship::class, 'targetAttribute' => ['ship_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'ship_id' => 'Ship ID',
            'priority' => 'Priority',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Cabins]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCabins(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Cabin::class, ['deck_id' => 'id']);
    }

    /**
     * Gets query for [[Ship]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShip(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Ship::class, ['id' => 'ship_id']);
    }
}
