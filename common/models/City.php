<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "city".
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
 *
 * @property Country $country
 * @property Cruise[] $cruises
 * @property Cruise[] $cruises0
 * @property Port[] $ports
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'country_id'], 'required'],
            [['description'], 'string'],
            [['country_id'], 'default', 'value' => null],
            [['country_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'logo', 'long', 'lat'], 'string', 'max' => 255],
            [['slug'], 'unique'],
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
            'description' => 'Description',
            'logo' => 'Logo',
            'country_id' => 'Country ID',
            'long' => 'Long',
            'lat' => 'Lat',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
        return $this->hasMany(Cruise::class, ['city_start_id' => 'id']);
    }

    /**
     * Gets query for [[Cruises0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruises0()
    {
        return $this->hasMany(Cruise::class, ['city_end_id' => 'id']);
    }

    /**
     * Gets query for [[Ports]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPorts()
    {
        return $this->hasMany(Port::class, ['city_id' => 'id']);
    }
}
