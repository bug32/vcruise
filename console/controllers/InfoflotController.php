<?php

namespace console\controllers;

use console\models\Ship;
use console\services\providers\infoflot\parsers\CruiseParse;
use yii\console\Controller;

class InfoflotController extends Controller
{

    public function actionShip(): void
    {
        $parserShip = new \console\services\providers\infoflot\parsers\ShipParser();
        $parserShip->run();
    }

    public function actionCruise():void
    {
        $parserCruise = new CruiseParse();
        $parserCruise->run();
    }

    public function actionService(): void
    {

        $otherParse = new \console\services\providers\infoflot\parsers\OtherParser();
        $otherParse->runService();

    }

    public function actionRiver():void
    {
        $otherParse = new \console\services\providers\infoflot\parsers\OtherParser();
        $otherParse->runRiver();
    }

    public function actionCity(): void
    {
        $otherParse = new \console\services\providers\infoflot\parsers\OtherParser();
        $otherParse->runCity();
    }

    public function actionCountry():void
    {
        $otherParse = new \console\services\providers\infoflot\parsers\OtherParser();
        $otherParse->runCountry();
    }

    public function actionPort():void
    {

        $port = new \console\services\providers\infoflot\parsers\OtherParser();
        $port->runPort();

    }



}