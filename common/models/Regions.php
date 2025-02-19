<?php

namespace common\models;

use common\models\relations\CruiseRegionRelations;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "regions".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CruiseRegionRelations[] $cruiseRegionRelations
 * @property Cruises[] $cruises
 */
class Regions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'regions';
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CruiseRegionRelations]].
     *
     * @return ActiveQuery
     */
    public function getCruiseRegionRelations(): ActiveQuery
    {
        return $this->hasMany(CruiseRegionRelations::class, ['region_id' => 'id']);
    }

    /**
     * Gets query for [[Cruises]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCruises(): ActiveQuery
    {
        return $this->hasMany(Cruises::class, ['id' => 'cruise_id'])->viaTable('cruise_region_relations', ['region_id' => 'id']);
    }
}
