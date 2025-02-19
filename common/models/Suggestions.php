<?php

namespace common\models;

use common\models\relations\CruiseSuggestionRelations;
use common\models\relations\SuggestionShipRelations;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "suggestions".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $label Название для отображения в интерфейсе
 * @property string|null $description
 * @property string|null $icon
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CruiseSuggestionRelations[] $cruiseSuggestionRelations
 * @property Cruises[] $cruises
 * @property Ships[] $ships
 * @property SuggestionShipRelations[] $suggestionShipRelations
 */
class Suggestions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'suggestions';
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
            [['name', 'slug', 'label', 'icon'], 'string', 'max' => 255],
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
            'label' => 'Название для отображения в интерфейсе',
            'description' => 'Description',
            'icon' => 'Icon',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CruiseSuggestionRelations]].
     *
     * @return ActiveQuery
     */
    public function getCruiseSuggestionRelations(): ActiveQuery
    {
        return $this->hasMany(CruiseSuggestionRelations::class, ['suggestion_id' => 'id']);
    }

    /**
     * Gets query for [[Cruises]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCruises(): ActiveQuery
    {
        return $this->hasMany(Cruises::class, ['id' => 'cruise_id'])->viaTable('cruise_suggestion_relations', ['suggestion_id' => 'id']);
    }

    /**
     * Gets query for [[Ships]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getShips(): ActiveQuery
    {
        return $this->hasMany(Ships::class, ['id' => 'ship_id'])->viaTable('suggestion_ship_relations', ['suggestion_id' => 'id']);
    }

    /**
     * Gets query for [[SuggestionShipRelations]].
     *
     * @return ActiveQuery
     */
    public function getSuggestionShipRelations(): ActiveQuery
    {
        return $this->hasMany(SuggestionShipRelations::class, ['suggestion_id' => 'id']);
    }
}
