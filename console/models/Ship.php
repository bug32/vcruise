<?php

namespace console\models;

use Random\RandomException;
use yii\base\ErrorException;
use yii\db\Exception;
use yii\helpers\FileHelper;

class Ship extends \common\models\Ships
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
        $model->operatorId = 1;
        $model->typeId     = 1;
        $model->status     = 1;
        $model->priority   = 1;
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