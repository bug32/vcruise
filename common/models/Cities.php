<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery as ActiveQueryAlias;

/**
 * This is the model class for table "cities".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $logo
 * @property int $country_id
 * @property string|null $long
 * @property string|null $lat
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $is_filter Показывать в фильтре
 *
 * @property CityMedias[] $cityMedias
 * @property Countries $country
 * @property Cruises[] $cruises
 * @property Cruises[] $cruises0
 * @property Ports[] $ports
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'cities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug', 'country_id'], 'required'],
            [['description'], 'string'],
            [['country_id', 'is_filter'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'logo', 'long', 'lat'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::class, 'targetAttribute' => ['country_id' => 'id']],
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
            'logo' => 'Logo',
            'country_id' => 'Country ID',
            'long' => 'Long',
            'lat' => 'Lat',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_filter' => 'Показывать в фильтре',
        ];
    }

    /**
     * Gets query for [[CityMedias]].
     *
     * @return ActiveQueryAlias
     */
    public function getCityMedias(): ActiveQueryAlias
    {
        return $this->hasMany(CityMedias::class, ['city_id' => 'id']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQueryAlias
     */
    public function getCountry(): ActiveQueryAlias
    {
        return $this->hasOne(Countries::class, ['id' => 'country_id']);
    }

    /**
     * Gets query for [[Cruises]].
     *
     * @return ActiveQueryAlias
     */
    public function getCruises(): ActiveQueryAlias
    {
        return $this->hasMany(Cruises::class, ['city_end_id' => 'id']);
    }

    /**
     * Gets query for [[Cruises0]].
     *
     * @return ActiveQueryAlias
     */
    public function getCruises0(): ActiveQueryAlias
    {
        return $this->hasMany(Cruises::class, ['city_start_id' => 'id']);
    }

    /**
     * Gets query for [[Ports]].
     *
     * @return ActiveQueryAlias
     */
    public function getPorts(): ActiveQueryAlias
    {
        return $this->hasMany(Ports::class, ['city_id' => 'id']);
    }
}
