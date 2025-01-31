<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "port".
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
 * @property City $city
 * @property Country $country
 * @property Cruise[] $cruises
 * @property Cruise[] $cruises0
 * @property Dock[] $docks
 */
class Port extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'port';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['city_id', 'country_id'], 'default', 'value' => null],
            [['city_id', 'country_id'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'address', 'coordinates', 'map'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['country_id' => 'id']],
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
            'city_id' => 'City ID',
            'description' => 'Description',
            'address' => 'Address',
            'coordinates' => 'Coordinates',
            'map' => 'Map',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'country_id' => 'Country ID',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * Gets query for [[Cruises]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruises()
    {
        return $this->hasMany(Cruise::class, ['port_start_id' => 'id']);
    }

    /**
     * Gets query for [[Cruises0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruises0()
    {
        return $this->hasMany(Cruise::class, ['port_end_id' => 'id']);
    }

    /**
     * Gets query for [[Docks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocks()
    {
        return $this->hasMany(Dock::class, ['port_id' => 'id']);
    }
}
