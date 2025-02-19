<?php

namespace common\models\relations;

use Yii;

/**
 * This is the model class for table "cruise_river_relations".
 *
 * @property int $cruise_id
 * @property int $river_id
 *
 * @property Cruises $cruise
 * @property Rivers $river
 */
class CruiseRiverRelations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cruise_river_relations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cruise_id', 'river_id'], 'required'],
            [['cruise_id', 'river_id'], 'integer'],
            [['cruise_id', 'river_id'], 'unique', 'targetAttribute' => ['cruise_id', 'river_id']],
            [['cruise_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cruises::class, 'targetAttribute' => ['cruise_id' => 'id']],
            [['river_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rivers::class, 'targetAttribute' => ['river_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cruise_id' => 'Cruise ID',
            'river_id' => 'River ID',
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
     * Gets query for [[River]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRiver()
    {
        return $this->hasOne(Rivers::class, ['id' => 'river_id']);
    }
}
