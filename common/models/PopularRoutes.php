<?php

namespace common\models;

use common\models\relations\CruisePopularRouteRelations;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "popular_routes".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $is_filter Показывать в фильтре
 *
 * @property CruisePopularRouteRelations[] $cruisePopularRouteRelations
 * @property Cruises[] $cruises
 */
class PopularRoutes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'popular_routes';
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
            [['is_filter'], 'integer'],
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
            'is_filter' => 'Показывать в фильтре',
        ];
    }

    /**
     * Gets query for [[CruisePopularRouteRelations]].
     *
     * @return ActiveQuery
     */
    public function getCruisePopularRouteRelations(): ActiveQuery
    {
        return $this->hasMany(CruisePopularRouteRelations::class, ['popular_route_id' => 'id']);
    }

    /**
     * Gets query for [[Cruises]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCruises(): ActiveQuery
    {
        return $this->hasMany(Cruises::class, ['id' => 'cruise_id'])->viaTable('cruise_popular_route_relations', ['popular_route_id' => 'id']);
    }
}
