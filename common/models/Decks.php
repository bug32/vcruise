<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "decks".
 *
 * @property int         $id
 * @property int         $ship_id
 * @property int         $priority
 * @property string      $name
 * @property string|null $description
 * @property int|null    $status
 * @property string      $created_at
 * @property string      $updated_at
 *
 * @property Cabins[]    $cabins
 * @property Ships       $ship
 */
class Decks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'decks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['ship_id', 'name'], 'required'],
            [['ship_id', 'priority', 'status'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['ship_id'], 'exist', 'skipOnError'     => TRUE, 'targetClass' => Ships::class,
                                   'targetAttribute' => ['ship_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels():array
    {
        return [
            'id'          => 'ID',
            'ship_id'     => 'Ship ID',
            'priority'    => 'Priority',
            'name'        => 'Name',
            'description' => 'Description',
            'status'      => 'Status',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Cabins]].
     *
     * @return ActiveQuery
     */
    public function getCabins(): ActiveQuery
    {
        return $this->hasMany(Cabins::class, ['deck_id' => 'id']);
    }

    /**
     * Gets query for [[Ship]].
     *
     * @return ActiveQuery
     */
    public function getShip(): ActiveQuery
    {
        return $this->hasOne(Ships::class, ['id' => 'ship_id']);
    }
}
