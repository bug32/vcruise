<?php

namespace console\models;

use Random\RandomException;
use yii\base\ErrorException;
use yii\db\Exception;
use yii\helpers\FileHelper;

class Ship extends \common\models\Ship
{

    /**
     * @return int
     * @throws Exception
     * @throws RandomException
     */
    public static function createEmpty(): int
    {
        $tempName          = md5(time() . '' . random_int(0, 99999));
        $model             = new self();
        $model->name       = $tempName;
        $model->slug       = $tempName;
        $model->operatorId = 0;
        $model->typeId     = 0;
        $model->status     = 0;
        $model->priority   = 0;
        if ($model->save()) {
            return $model->id;
        }

        throw new Exception('Неудалось создать корабль');
    }

    public function delete()
    {
        $ship_id = $this->id;
        if (parent::delete()) {
            FileHelper::removeDirectory(\Yii::getAlias('@staticPublic') . '/ships/' . $ship_id);
            return TRUE;
        }

        return FALSE;
    }

    public static function deleteId(string $id): bool
    {
        try {
            if (self::deleteAll(['id' => $id])) {
                FileHelper::removeDirectory(\Yii::getAlias('@staticPublic') . '/ships/' . $id);
                return TRUE;
            }
        } catch (\Throwable $e) {
            return FALSE;
        }

        return FALSE;
    }

}