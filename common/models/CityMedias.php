<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery as ActiveQueryAlias;

/**
 * This is the model class for table "city_medias".
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
 * @property Cities $city
 */
class CityMedias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'city_medias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['city_id', 'url'], 'required'],
            [['city_id', 'size', 'priority'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['alt', 'mime_type', 'url'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
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
     * @return ActiveQueryAlias
     */
    public function getCity(): ActiveQueryAlias
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }
}
