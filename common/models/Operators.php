<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "operators".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $status
 * @property int|null $rating
 * @property string|null $description
 * @property string|null $logo
 * @property string|null $url
 * @property string|null $phone
 * @property string|null $email
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Ships[] $ships
 */
class Operators extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'operators';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['status', 'rating'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'logo', 'url', 'phone', 'email'], 'string', 'max' => 255],
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
     * @return ActiveQuery
     */
    public function getShips(): ActiveQuery
    {
        return $this->hasMany(Ships::class, ['operatorId' => 'id']);
    }
}
