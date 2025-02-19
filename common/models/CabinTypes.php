<?php

namespace common\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "cabin_types".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $ship_id
 * @property int $priority
 * @property int $isEco
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CabinTypeMedias[] $cabinTypeMedias
 * @property CabinTypeServiceRelations[] $cabinTypeServiceRelations
 * @property Cabins[] $cabins
 * @property Services[] $services
 * @property Ships $ship
 */
class CabinTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'cabin_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'ship_id'], 'required'],
            [['description'], 'string'],
            [['ship_id', 'priority', 'isEco'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['ship_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ships::class, 'targetAttribute' => ['ship_id' => 'id']],
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
            'description' => 'Description',
            'ship_id' => 'Ship ID',
            'priority' => 'Priority',
            'isEco' => 'Is Eco',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CabinTypeMedias]].
     *
     * @return ActiveQuery
     */
    public function getCabinTypeMedias(): ActiveQuery
    {
        return $this->hasMany(CabinTypeMedias::class, ['cabin_type_id' => 'id']);
    }

    /**
     * Gets query for [[CabinTypeServiceRelations]].
     *
     * @return ActiveQuery
     */
    public function getCabinTypeServiceRelations(): ActiveQuery
    {
        return $this->hasMany(CabinTypeServiceRelations::class, ['cabin_type_id' => 'id']);
    }

    /**
     * Gets query for [[Cabins]].
     *
     * @return ActiveQuery
     */
    public function getCabins(): ActiveQuery
    {
        return $this->hasMany(Cabins::class, ['cabin_type_id' => 'id']);
    }

    /**
     * Gets query for [[Services]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getServices(): ActiveQuery
    {
        return $this->hasMany(Services::class, ['id' => 'service_id'])->viaTable('cabin_type_service_relations', ['cabin_type_id' => 'id']);
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
