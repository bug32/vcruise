<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cabin_type".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $priority
 * @property bool $isEco
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Cabin[] $cabins
 */
class CabinType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%cabin_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['description'], 'string'],
            [['priority'], 'default', 'value' => null],
            [['priority'], 'integer'],
            [['isEco'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
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
            'description' => 'Description',
            'priority' => 'Priority',
            'isEco' => 'Is Eco',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Cabins]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCabins(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Cabin::class, ['cabin_type_id' => 'id']);
    }
}
