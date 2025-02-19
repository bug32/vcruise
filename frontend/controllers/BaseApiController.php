<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;

use yii\rest\Serializer;
use yii\web\Controller;
use yii\web\Response;

class BaseApiController extends Controller
{
    /**
     * @var string|array the configuration for creating the serializer that formats the response data.
     */
    public string|array $serializer = Serializer::class;
    /**
     * {@inheritdoc}
     */
    public $enableCsrfValidation = false;

    public function behaviors(): array
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                'cors' => [
//                    'Access-Control-Allow-Origin' => ['*'],
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    //Разрешает отдавать экзотические хедеры
                    'Access-Control-Expose-Headers' => ['*'],
                    //При использовании Access-Control-Allow-Credentials: true всегда
                    //используется Access-Control-Allow-Origin: домен — при использовании * браузер не получит ответ.
                    'Access-Control-Allow-Credentials' => false,
                    'Access-Control-Max-Age' => 3600,
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ],
            ],
            /*
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
            */
            /*
            'rateLimiter' => [
                'class' => RateLimiter::class,
            ],
            */
            /*
            'authenticator' => [
                'class' => HttpBearerAuth::class,
                'except' => ['options', 'login']
            ]
            */
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function sendResponse($result, $message): mixed
    {
        $response = [
            'success' => TRUE,
            'items'    => $result,
            'message' => $message
        ];

       return $this->serializeData( $response);
    }

    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    protected function verbs(): array
    {
        return [];
    }

    /**
     * Serializes the specified data.
     * The default implementation will create a serializer based on the configuration given by [[serializer]].
     * It then uses the serializer to serialize the given data.
     *
     * @param array $data the data to be serialized
     *
     * @return mixed the serialized data.
     * @throws InvalidConfigException
     */
    protected function serializeData(array $data): mixed
    {
      return Yii::createObject($this->serializer)->serialize($data);
    }

}