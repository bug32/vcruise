<?php

namespace common\models\relations;

use Yii;

/**
 * This is the model class for table "cruise_popular_route_relations".
 *
 * @property int $cruise_id
 * @property int $popular_route_id
 *
 * @property Cruises $cruise
 * @property PopularRoutes $popularRoute
 */
class CruisePopularRouteRelations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cruise_popular_route_relations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cruise_id', 'popular_route_id'], 'required'],
            [['cruise_id', 'popular_route_id'], 'integer'],
            [['cruise_id', 'popular_route_id'], 'unique', 'targetAttribute' => ['cruise_id', 'popular_route_id']],
            [['cruise_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cruises::class, 'targetAttribute' => ['cruise_id' => 'id']],
            [['popular_route_id'], 'exist', 'skipOnError' => true, 'targetClass' => PopularRoutes::class, 'targetAttribute' => ['popular_route_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cruise_id' => 'Cruise ID',
            'popular_route_id' => 'Popular Route ID',
        ];
    }

    /**
     * Gets query for [[Cruise]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruise()
    {
        return $this->hasOne(Cruises::class, ['id' => 'cruise_id']);
    }

    /**
     * Gets query for [[PopularRoute]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPopularRoute()
    {
        return $this->hasOne(PopularRoutes::class, ['id' => 'popular_route_id']);
    }
}
