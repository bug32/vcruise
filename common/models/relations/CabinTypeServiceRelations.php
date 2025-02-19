<?php

namespace common\models\relations;

use Yii;

/**
 * This is the model class for table "cabin_type_service_relations".
 *
 * @property int $cabin_type_id
 * @property int $service_id
 *
 * @property CabinTypes $cabinType
 * @property Services $service
 */
class CabinTypeServiceRelations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cabin_type_service_relations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cabin_type_id', 'service_id'], 'required'],
            [['cabin_type_id', 'service_id'], 'integer'],
            [['cabin_type_id', 'service_id'], 'unique', 'targetAttribute' => ['cabin_type_id', 'service_id']],
            [['cabin_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CabinTypes::class, 'targetAttribute' => ['cabin_type_id' => 'id']],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Services::class, 'targetAttribute' => ['service_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cabin_type_id' => 'Cabin Type ID',
            'service_id' => 'Service ID',
        ];
    }

    /**
     * Gets query for [[CabinType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCabinType()
    {
        return $this->hasOne(CabinTypes::class, ['id' => 'cabin_type_id']);
    }

    /**
     * Gets query for [[Service]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Services::class, ['id' => 'service_id']);
    }
}
