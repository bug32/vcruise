<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "popular_route".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CruisePopularRouteRelation[] $cruisePopularRouteRelations
 * @property Cruise[] $cruises
 */
class PopularRoute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'popular_route';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
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
     * Gets query for [[CruisePopularRouteRelations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruisePopularRouteRelations()
    {
        return $this->hasMany(CruisePopularRouteRelation::class, ['popular_route_id' => 'id']);
    }

    /**
     * Gets query for [[Cruises]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruises()
    {
        return $this->hasMany(Cruise::class, ['id' => 'cruise_id'])->viaTable('cruise_popular_route_relation', ['popular_route_id' => 'id']);
    }
}
