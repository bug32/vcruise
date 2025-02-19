<?php

namespace common\models;

use common\models\relations\CabinTypeServiceRelations;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $icon
 * @property string|null $description
 * @property int|null $priority
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CabinTypeServiceRelations[] $cabinTypeServiceRelations
 * @property CabinTypes[] $cabinTypes
 */
class Services extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['description'], 'string'],
            [['priority'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'icon'], 'string', 'max' => 255],
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
            'icon' => 'Icon',
            'description' => 'Description',
            'priority' => 'Priority',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CabinTypeServiceRelations]].
     *
     * @return ActiveQuery
     */
    public function getCabinTypeServiceRelations(): ActiveQuery
    {
        return $this->hasMany(CabinTypeServiceRelations::class, ['service_id' => 'id']);
    }

    /**
     * Gets query for [[CabinTypes]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCabinTypes(): ActiveQuery
    {
        return $this->hasMany(CabinTypes::class, ['id' => 'cabin_type_id'])->viaTable('cabin_type_service_relations', ['service_id' => 'id']);
    }
}
