<?php

namespace common\models;

use Pimple\Tests\Fixtures\Service;
use Yii;

/**
 * This is the model class for table "cabin_type".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $ship_id
 * @property int $priority
 * @property bool $isEco
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Cabin[] $cabins
 * @property Service[] $services
 */
class CabinType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cabin_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ship_id'], 'required'],
            [['description'], 'string'],
            [['ship_id', 'priority'], 'default', 'value' => null],
            [['ship_id', 'priority'], 'integer'],
            [['isEco'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'ship_id' => 'Ship ID',
            'priority' => 'Priority',
            'isEco' => 'Is Eco',
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
        return $this->hasMany(Cabin::class, ['cabin_type_id' => 'id']);
    }

    /**
     * Gets query for [[Services]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::class, ['id' => 'service_id'])->viaTable('cabin_type_service_relation', ['cabin_type_id' => 'id']);
    }
}
