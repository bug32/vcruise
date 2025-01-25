<?php

namespace common\models\cruise;

use Yii;

/**
 * This is the model class for table "cruise_media".
 *
 * @property int $id
 * @property int $cruise_id
 * @property string $name
 * @property string $mime_type
 * @property string $url
 * @property int $size
 * @property int $priority
 * @property string $created_at
 * @property string $updated_at
 */
class CruiseMedia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cruise_media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cruise_id', 'name', 'mime_type', 'url', 'size'], 'required'],
            [['cruise_id', 'size', 'priority'], 'default', 'value' => null],
            [['cruise_id', 'size', 'priority'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'mime_type', 'url'], 'string', 'max' => 255],
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
