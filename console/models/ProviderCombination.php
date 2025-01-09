<?php

namespace console\models;

class ProviderCombination extends \common\models\ProviderCombination
{

    protected static array $ships = [];

    public function getShips(string $providerName, string $model ): array
    {
        $out = self::find()->where(['provider_name' => $providerName, 'model_name' => $model])->asArray()->all();

        return [];
    }
}