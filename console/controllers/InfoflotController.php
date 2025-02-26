<?php

namespace console\controllers;

use console\models\Ship;
use console\services\providers\infoflot\parsers\CruiseParse;
use console\services\providers\infoflot\parsers\OtherParser;
use yii\console\Controller;

class InfoflotController extends Controller
{

    public function actionStart()
    {
        $other = new OtherParser();
        echo 'Run services'.PHP_EOL;
        $other->runService();

        echo 'Run Country'.PHP_EOL;
        $other->runCountry();

        echo 'Run City'.PHP_EOL;
        $other->runCity();

        echo 'run Regions '.PHP_EOL;
        $other->runRegions();

        echo 'run Port '.PHP_EOL;
        $other->runPort();

        echo 'run Places '.PHP_EOL;
        $other->runPlaces();

        echo 'run River '.PHP_EOL;
        $other->runRiver();

        echo 'run Places'.PHP_EOL;
        $other->runPlaces();
    }

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

    public function actionRoute()
    {
        $route = new OtherParser();
        $route->runRoute();
    }

    public function actionRegions()
    {
        $regions = new OtherParser();
        $regions->runRegions();
    }

    public function actionPlaces()
    {
        $places = new OtherParser();
        $places->runPlaces();

    }



}