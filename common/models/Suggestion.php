<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "suggestion".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $icon
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CruiseSuggestionRelation[] $cruiseSuggestionRelations
 * @property Cruise[] $cruises
 */
class Suggestion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'suggestion';
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
            [['name', 'slug', 'icon'], 'string', 'max' => 255],
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
            'icon' => 'Icon',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CruiseSuggestionRelations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruiseSuggestionRelations()
    {
        return $this->hasMany(CruiseSuggestionRelation::class, ['suggestion_id' => 'id']);
    }

    /**
     * Gets query for [[Cruises]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruises()
    {
        return $this->hasMany(Cruise::class, ['id' => 'cruise_id'])->viaTable('cruise_suggestion_relation', ['suggestion_id' => 'id']);
    }
}
