<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery as ActiveQueryAlias;

/**
 * This is the model class for table "countries".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $flag
 * @property string|null $code
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Cities[] $cities
 * @property Ports[] $ports
 */
class Countries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'countries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'flag', 'code'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['code'], 'unique'],
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
            'flag' => 'Flag',
            'code' => 'Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Cities]].
     *
     * @return ActiveQueryAlias
     */
    public function getCities(): ActiveQueryAlias
    {
        return $this->hasMany(Cities::class, ['country_id' => 'id']);
    }

    /**
     * Gets query for [[Ports]].
     *
     * @return ActiveQueryAlias
     */
    public function getPorts(): ActiveQueryAlias
    {
        return $this->hasMany(Ports::class, ['country_id' => 'id']);
    }
}
