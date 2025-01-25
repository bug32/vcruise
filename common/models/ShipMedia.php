<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ship_media".
 *
 * @property int $id
 * @property int|null $ship_id
 * @property string $name
 * @property string|null $key Для объединения картинок в группы по ключу
 * @property string $mime_type
 * @property string $url
 * @property int $size
 * @property int $priority
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Ship $ship
 */
class ShipMedia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ship_media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ship_id', 'size', 'priority'], 'default', 'value' => null],
            [['ship_id', 'size', 'priority'], 'integer'],
            [['name', 'mime_type', 'url', 'size'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'key', 'mime_type', 'url'], 'string', 'max' => 255],
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
            'ship_id' => 'Ship ID',
            'name' => 'Name',
            'key' => 'Key',
            'mime_type' => 'Mime Type',
            'url' => 'Url',
            'size' => 'Size',
            'priority' => 'Priority',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Ship]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShip()
    {
        return $this->hasOne(Ship::class, ['id' => 'ship_id']);
    }
}
