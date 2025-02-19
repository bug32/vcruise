<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cabin_type_medias".
 *
 * @property int $id
 * @property int $cabin_type_id
 * @property string|null $name
 * @property string|null $alt
 * @property string|null $mime_type
 * @property string|null $url
 * @property int|null $size
 * @property int|null $priority
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CabinTypes $cabinType
 */
class CabinTypeMedias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'cabin_type_medias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['cabin_type_id'], 'required'],
            [['cabin_type_id', 'size', 'priority'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'alt', 'mime_type', 'url'], 'string', 'max' => 255],
            [['cabin_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CabinTypes::class, 'targetAttribute' => ['cabin_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'cabin_type_id' => 'Cabin Type ID',
            'name' => 'Name',
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
     * Gets query for [[CabinType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCabinType(): \yii\db\ActiveQuery
    {
        return $this->hasOne(CabinTypes::class, ['id' => 'cabin_type_id']);
    }
}
