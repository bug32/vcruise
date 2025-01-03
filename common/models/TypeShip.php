<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "type_ship".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $status
 * @property int $priority
 * @property string|null $icon
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Ship[] $ships
 */
class TypeShip extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'type_ship';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['status', 'priority'], 'default', 'value' => null],
            [['status', 'priority'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'icon'], 'string', 'max' => 255],
            [['slug', 'status'], 'unique', 'targetAttribute' => ['slug', 'status']],
            [['slug'], 'unique'],
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
     * @return \yii\db\ActiveQuery
     */
    public function getShips()
    {
        return $this->hasMany(Ship::class, ['typeId' => 'id']);
    }
}
