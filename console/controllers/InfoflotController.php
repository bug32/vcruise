<?php

namespace console\controllers;

use console\models\Ship;
use yii\console\Controller;

class InfoflotController extends Controller
{

    public function actionShip()
    {

        $parserShip = new \console\services\providers\infoflot\parsers\ShipParser();
        $parserShip->run();

        error_log('OK');

    }

}