<?php

namespace common\models\relations;

use Yii;

/**
 * This is the model class for table "suggestion_ship_relations".
 *
 * @property int $suggestion_id
 * @property int $ship_id
 * @property int $priority
 *
 * @property Ships $ship
 * @property Suggestions $suggestion
 */
class SuggestionShipRelations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'suggestion_ship_relations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['suggestion_id', 'ship_id'], 'required'],
            [['suggestion_id', 'ship_id', 'priority'], 'integer'],
            [['suggestion_id', 'ship_id'], 'unique', 'targetAttribute' => ['suggestion_id', 'ship_id']],
            [['ship_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ships::class, 'targetAttribute' => ['ship_id' => 'id']],
            [['suggestion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Suggestions::class, 'targetAttribute' => ['suggestion_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'suggestion_id' => 'Suggestion ID',
            'ship_id' => 'Ship ID',
            'priority' => 'Priority',
        ];
    }

    /**
     * Gets query for [[Ship]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShip()
    {
        return $this->hasOne(Ships::class, ['id' => 'ship_id']);
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
