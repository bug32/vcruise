<?php

namespace common\models\relations;

use Yii;

/**
 * This is the model class for table "cruise_suggestion_relations".
 *
 * @property int $cruise_id
 * @property int $suggestion_id
 *
 * @property Cruises $cruise
 * @property Suggestions $suggestion
 */
class CruiseSuggestionRelations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cruise_suggestion_relations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cruise_id', 'suggestion_id'], 'required'],
            [['cruise_id', 'suggestion_id'], 'integer'],
            [['cruise_id', 'suggestion_id'], 'unique', 'targetAttribute' => ['cruise_id', 'suggestion_id']],
            [['cruise_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cruises::class, 'targetAttribute' => ['cruise_id' => 'id']],
            [['suggestion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Suggestions::class, 'targetAttribute' => ['suggestion_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cruise_id' => 'Cruise ID',
            'suggestion_id' => 'Suggestion ID',
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
     * Gets query for [[Suggestion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSuggestion()
    {
        return $this->hasOne(Suggestions::class, ['id' => 'suggestion_id']);
    }
}
