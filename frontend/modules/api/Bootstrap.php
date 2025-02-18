<?php

namespace frontend\modules\api;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $urlManager = $app->getUrlManager();
        $urlManager->addRules([
            Module::MODULE_ID.'/<controller:\w+>/<action:\w+>/<id:\d+>' => Module::MODULE_ID.'/<controller>/<action>',
            Module::MODULE_ID.'/<controller:\w+>/<action:\w+>' => Module::MODULE_ID.'/<controller>/<action>',
            Module::MODULE_ID.'/<controller:\w+>' => Module::MODULE_ID.'/<controller>/index',
        ]);
    }
}