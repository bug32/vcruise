<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dock".
 *
 * @property int $id
 * @property int $port_id
 * @property string $name
 * @property string|null $address
 * @property string|null $coordinates
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Port $port
 */
class Dock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['port_id', 'name'], 'required'],
            [['port_id'], 'default', 'value' => null],
            [['port_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'address', 'coordinates'], 'string', 'max' => 255],
            [['port_id'], 'exist', 'skipOnError' => true, 'targetClass' => Port::class, 'targetAttribute' => ['port_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
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
     * @return \yii\db\ActiveQuery
     */
    public function getPort()
    {
        return $this->hasOne(Port::class, ['id' => 'port_id']);
    }
}
