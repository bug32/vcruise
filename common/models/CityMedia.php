<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "city_media".
 *
 * @property int $id
 * @property int $city_id
 * @property string|null $alt
 * @property string|null $mime_type
 * @property string $url
 * @property int|null $size
 * @property int|null $priority
 * @property string $created_at
 * @property string $updated_at
 *
 * @property City $city
 */
class CityMedia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city_media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'url'], 'required'],
            [['city_id', 'size', 'priority'], 'default', 'value' => null],
            [['city_id', 'size', 'priority'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['alt', 'mime_type', 'url'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'alt' => 'Alt',
            'mime_type' => 'Mime Type',
            'url' => 'Url',
            'size' => 'Size',
            'priority' => 'Priority',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }
}
