<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "ports".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $city_id
 * @property string|null $description
 * @property string|null $address
 * @property string|null $coordinates Координаты порта
 * @property string|null $map ссылка на карту яндекс
 * @property string $created_at
 * @property string $updated_at
 * @property int $country_id
 *
 * @property Cities $city
 * @property Countries $country
 * @property Cruises[] $cruises
 * @property Cruises[] $cruises0
 * @property Docks[] $docks
 */
class Ports extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'ports';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['city_id', 'country_id'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'address', 'coordinates', 'map'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
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
            'city_id' => 'City ID',
            'description' => 'Description',
            'address' => 'Address',
            'coordinates' => 'Координаты порта',
            'map' => 'ссылка на карту яндекс',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'country_id' => 'Country ID',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Countries::class, ['id' => 'country_id']);
    }

    /**
     * Gets query for [[Cruises]].
     *
     * @return ActiveQuery
     */
    public function getCruises(): ActiveQuery
    {
        return $this->hasMany(Cruises::class, ['port_end_id' => 'id']);
    }

    /**
     * Gets query for [[Cruises0]].
     *
     * @return ActiveQuery
     */
    public function getCruises0(): ActiveQuery
    {
        return $this->hasMany(Cruises::class, ['port_start_id' => 'id']);
    }

    /**
     * Gets query for [[Docks]].
     *
     * @return ActiveQuery
     */
    public function getDocks(): ActiveQuery
    {
        return $this->hasMany(Docks::class, ['port_id' => 'id']);
    }
}
