<?php

namespace common\models\relations;

use Yii;

/**
 * This is the model class for table "cruise_region_relations".
 *
 * @property int $cruise_id
 * @property int $region_id
 *
 * @property Cruises $cruise
 * @property Regions $region
 */
class CruiseRegionRelations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cruise_region_relations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cruise_id', 'region_id'], 'required'],
            [['cruise_id', 'region_id'], 'integer'],
            [['cruise_id', 'region_id'], 'unique', 'targetAttribute' => ['cruise_id', 'region_id']],
            [['cruise_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cruises::class, 'targetAttribute' => ['cruise_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Regions::class, 'targetAttribute' => ['region_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cruise_id' => 'Cruise ID',
            'region_id' => 'Region ID',
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
     * Gets query for [[Region]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Regions::class, ['id' => 'region_id']);
    }
}
