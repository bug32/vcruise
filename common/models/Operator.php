<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "operator".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $status
 * @property int $rating
 * @property string|null $description
 * @property string|null $logo
 * @property string|null $url
 * @property string|null $phone
 * @property string|null $email
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Ship[] $ships
 */
class Operator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%operators}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['status', 'rating'], 'default', 'value' => null],
            [['status', 'rating'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'logo', 'url', 'phone', 'email'], 'string', 'max' => 255],
            [['slug', 'status'], 'unique', 'targetAttribute' => ['slug', 'status']],
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
            'status' => 'Status',
            'rating' => 'Rating',
            'description' => 'Description',
            'logo' => 'Logo',
            'url' => 'Url',
            'phone' => 'Phone',
            'email' => 'Email',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Ships]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShips(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Ship::class, ['operatorId' => 'id']);
    }
}
