<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "docks".
 *
 * @property int $id
 * @property int $port_id
 * @property string $name
 * @property string|null $address
 * @property string|null $coordinates
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Ports $port
 */
class Docks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'docks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['port_id', 'name'], 'required'],
            [['port_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'address', 'coordinates'], 'string', 'max' => 255],
            [['port_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ports::class, 'targetAttribute' => ['port_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'port_id' => 'Port ID',
            'name' => 'Name',
            'address' => 'Address',
            'coordinates' => 'Coordinates',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Port]].
     *
     * @return ActiveQuery
     */
    public function getPort(): ActiveQuery
    {
        return $this->hasOne(Ports::class, ['id' => 'port_id']);
    }
}
