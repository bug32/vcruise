<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "ship_medias".
 *
 * @property int         $id
 * @property int|null    $ship_id
 * @property string|null $alt
 * @property string      $name
 * @property string|null $key Для объединения картинок в группы по ключу
 * @property string      $mime_type
 * @property string      $url
 * @property int         $size
 * @property int         $priority
 * @property string      $created_at
 * @property string      $updated_at
 *
 * @property Ships       $ship
 */
class ShipMedias extends \yii\db\ActiveRecord
{
    public const KEY_GALLERY             = 'gallery';
    public const KEY_PHOTO               = 'photo';
    public const KEY_SCHEME              = 'scheme';
    public const KEY_CAPITAN             = 'captainPhoto';
    public const KEY_DIRECTOR            = 'cruiseDirectorPhoto';
    public const KEY_DIRECTOR_RESTAURANT = 'restaurantDirectorPhoto';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'ship_medias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['ship_id', 'size', 'priority'], 'integer'],
            [['name', 'mime_type', 'url', 'size'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['alt', 'name', 'key', 'mime_type', 'url'], 'string', 'max' => 255],
            [['ship_id'], 'exist', 'skipOnError'     => TRUE, 'targetClass' => Ships::class,
                                   'targetAttribute' => ['ship_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'ship_id'    => 'Ship ID',
            'alt'        => 'Alt',
            'name'       => 'Name',
            'key'        => 'Для объединения картинок в группы по ключу',
            'mime_type'  => 'Mime Type',
            'url'        => 'Url',
            'size'       => 'Size',
            'priority'   => 'Priority',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Ship]].
     *
     * @return ActiveQuery
     */
    public function getShip(): ActiveQuery
    {
        return $this->hasOne(Ships::class, ['id' => 'ship_id']);
    }
}
