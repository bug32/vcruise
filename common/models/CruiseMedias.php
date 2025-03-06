<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cruise_medias".
 *
 * @property int $id
 * @property int $cruise_id
 * @property string|null $alt
 * @property string|null $name
 * @property string|null $mime_type
 * @property string $url
 * @property int|null $size
 * @property int|null $priority
 * @property string $created_at
 * @property string $updated_at
 */
class CruiseMedias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cruise_medias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cruise_id', 'url'], 'required'],
            [['cruise_id', 'size', 'priority'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['alt', 'name', 'mime_type', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cruise_id' => 'Cruise ID',
            'alt' => 'Alt',
            'name' => 'Name',
            'mime_type' => 'Mime Type',
            'url' => 'Url',
            'size' => 'Size',
            'priority' => 'Priority',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
