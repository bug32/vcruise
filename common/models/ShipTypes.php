<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "ship_types".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $status
 * @property int|null $priority
 * @property string|null $icon
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Ships[] $ships
 */
class ShipTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'ship_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['status', 'priority'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'icon'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['slug', 'status'], 'unique', 'targetAttribute' => ['slug', 'status']],
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
            'slug' => 'Slug',
            'status' => 'Status',
            'priority' => 'Priority',
            'icon' => 'Icon',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Ships]].
     *
     * @return ActiveQuery
     */
    public function getShips(): ActiveQuery
    {
        return $this->hasMany(Ships::class, ['typeId' => 'id']);
    }
}
