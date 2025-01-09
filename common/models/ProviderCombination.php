<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "provider_combination".
 *
 * @property int $id
 * @property string $provider_name
 * @property int $foreign_id
 * @property int $internal_id
 * @property string $model_name Модель связывания: ship, cruise, city и тд
 * @property string $created_at
 * @property string $updated_at
 */
class ProviderCombination extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provider_combination';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['provider_name', 'foreign_id', 'internal_id', 'model_name'], 'required'],
            [['foreign_id', 'internal_id'], 'default', 'value' => null],
            [['foreign_id', 'internal_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['provider_name', 'model_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'provider_name' => 'Provider Name',
            'foreign_id' => 'Foreign ID',
            'internal_id' => 'Internal ID',
            'model_name' => 'Model Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
