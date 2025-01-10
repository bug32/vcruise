<?php

namespace console\services\providers\infoflot;

use Codeception\Exception\ExternalUrlException;
use JsonException;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UrlNormalizer;

class InfoflotAPI
{
    public const PROVIDER_NAME = 'infoflot';

    public const               PROVIDER_MODEL_NAME_SHIP       = 'ship';
    public const               PROVIDER_MODEL_NAME_DECK       = 'deck';
    public const               PROVIDER_MODEL_NAME_CABIN      = 'cabin';
    public const               PROVIDER_MODEL_NAME_CABIN_TYPE = 'cabin_type';
    public const               PROVIDER_MODEL_NAME_CRUISE     = 'cruise';
    public const               PROVIDER_MODEL_NAME_PORT       = 'port';
    public const               PROVIDER_MODEL_NAME_CITY       = 'city';
    public const               PROVIDER_MODEL_NAME_COUNTRY       = 'country';
    public const               PROVIDER_MODEL_NAME_DOCK       = 'dock';
    public const               BASE_URL                       = 'https://restapi.infoflot.com';
    public const               TOKEN                          = "ddf71b33662078a069d2a1eacafefb33cc783bf6";
    public const               PAGE_LIMIT                     = 50;

    public const        URN_SHIP = '/ships';


    protected $_ships = [];

    public function __construct()
    {
    }

    /**
     * @throws JsonException
     * @throws \Exception
     */
    public function request(string $urn, $params = []): array
    {
        if (empty($urn)) {
            throw new ExternalUrlException('Url is empty');
        }

        //$url = self::BASE_URL . $url . '?limit=' . self::PAGE_LIMIT . '&key' . self::TOKEN;

        $params = ArrayHelper::merge($params, ['key' => self::TOKEN]);

        $url       = self::BASE_URL . $urn;
        $normalize = new UrlNormalizer();
        $url       = $normalize->normalizePathInfo($url, '');

        echo $url . '?' . http_build_query($params) . "\n";

        $ch = curl_init($url . '?' . http_build_query($params));

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = curl_exec($ch);
        curl_close($ch);

        return json_decode($data, TRUE, 512, JSON_THROW_ON_ERROR);
    }

    public function getServices(): array
    {
        return $this->request('/onboard-services');
    }

    protected function getRivers(): array
    {
        return $this->request('/rivers');
    }

    protected function getCities(): array
    {
        return $this->request('/cities');
    }

    /**
     * @throws JsonException
     */
    public function getShips($params = []): array
    {
        if (empty($this->_ships)) {
            return $this->_ships = $this->request(self::URN_SHIP);
        }
        return [];
    }

    /**
     * @throws \yii\db\Exception
     */
    protected function getProviderDeck()
    {

        $sql = "SELECT provider_combination.* 
            FROM provider_combination 
            WHERE provider_combination.provider_name = :provider_name AND provider_combination.model_name = :model_name";

        $providerDeck = Yii::$app->db->createCommand(
            $sql,
            [
                ':provider_name' => self::PROVIDER_NAME,
                ':model_name'    => self::PROVIDER_MODEL_NAME_DECK
            ]
        )->queryAll();

        if (empty($providerDeck)) {
            return [];
        }

        return ArrayHelper::index($providerDeck, 'foreign_id');
    }

    protected function setProviderDeck($foreignId, $internalId): void
    {
        Yii::$app->db->createCommand()->insert('provider_combination', [
            'provider_name' => self::PROVIDER_NAME,
            'foreign_id'    => $foreignId,
            'internal_id'   => $internalId,
            'model_name'    => self::PROVIDER_MODEL_NAME_DECK
        ])->execute();
    }

    /**
     * @throws \yii\db\Exception
     */
    protected function getProviderCabin(int|string $shipId): array
    {
        $sql = "SELECT provider_combination.* 
            FROM provider_combination 
            LEFT JOIN cabin ON cabin.ship_id = provider_combination.internal_id
            WHERE cabin.ship_id = :id AND provider_combination.provider_name = :provider_name AND provider_combination.model_name = :model_name";

        $providerCabin = Yii::$app->db->createCommand(
            $sql,
            [
                ':id'            => $shipId,
                ':provider_name' => self::PROVIDER_NAME,
                ':model_name'    => self::PROVIDER_MODEL_NAME_CABIN
            ]
        )->queryAll();

        if (empty($providerCabin)) {
            return [];
        }

        return ArrayHelper::index($providerCabin, 'foreign_id');
    }

    protected function setProviderCabin(mixed $foreignId, int $internalId): void
    {
        Yii::$app->db->createCommand()->insert('provider_combination', [
            'provider_name' => self::PROVIDER_NAME,
            'foreign_id'    => $foreignId,
            'internal_id'   => $internalId,
            'model_name'    => self::PROVIDER_MODEL_NAME_CABIN
        ])->execute();
    }

    protected function getProviderCabinType($shipId): array
    {
        $sql = "SELECT provider_combination.* 
            FROM provider_combination 
            WHERE provider_combination.provider_name = :provider_name AND 
                  provider_combination.model_name = :model_name";

        $providerCabin = Yii::$app->db->createCommand(
            $sql,
            [
                ':provider_name' => self::PROVIDER_NAME,
                ':model_name'    => self::PROVIDER_MODEL_NAME_CABIN_TYPE
            ]
        )->queryAll();

        if (empty($providerCabin)) {
            return [];
        }

        return ArrayHelper::index($providerCabin, 'foreign_id');
    }


    /** Сохранение картинок в папке image
     *
     * @param string $dir // каталог для сохранения файла
     *
     * @return string
     * @throws Exception
     */
    protected function getLoadPath(string $dir): string
    {
        // $loadPath = Yii::getAlias('@frontend') . '/web/public/' . $dir;
        $loadPath = Yii::getAlias('@staticPublic') . '/' . $dir;

        if (FileHelper::createDirectory($loadPath)) {
            return $loadPath . '/';
        }

        throw new Exception("Directory $dir was not created");

//
//        if (!file_exists($loadPath)) {
//            if (!mkdir($loadPath, 0777, TRUE) && !is_dir($loadPath)) {
//                throw new \RuntimeException(sprintf('Directory "%s" was not created', $loadPath));
//            }
//        }

    }

    /**
     * @param $file string // картинка для сохранения
     * @param $path string // путь для сохранения  ( ship/id )
     *
     * @return string
     */
    protected function saveFile(string $file, string $path): string
    {
        if (empty($file)) {
            return '';
        }
        $fileInfo = [];
        try {
            $absolutePath = $this->getLoadPath($path);

            $fileInfo = pathinfo($file);

            $fileName = md5($fileInfo['filename']) . '.' . $fileInfo['extension'];

            copy($file, $absolutePath . $fileName);

            return '/public/' . $path . '/' . $fileName;

        } catch (\Throwable $e) {
            return '';
        }
    }

    protected function clearText($text): array|string
    {
        if (empty($text)) {
            return '';
        }
        return str_replace(['&nbsp;', ' ', '\n', PHP_EOL], [' ', ' ', '', ''], trim($text));
    }

}