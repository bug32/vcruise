<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery as ActiveQueryAlias;

/**
 * This is the model class for table "cruise_type".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Cruises[] $cruises
 */
class CruiseType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'cruise_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Cruises]].
     *
     * @return ActiveQueryAlias
     */
    public function getCruises(): ActiveQueryAlias
    {
        return $this->hasMany(Cruises::class, ['type_id' => 'id']);
    }
}
