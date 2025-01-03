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
    public const BASE_URL      = 'https://restapi.infoflot.com';
    public const TOKEN         = "ddf71b33662078a069d2a1eacafefb33cc783bf6";
    public const PAGE_LIMIT    = 50;

    public const URN_SHIP = '/ships';

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

        $ch = curl_init($url . '?' . http_build_query($params));

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = curl_exec($ch);
        curl_close($ch);

        return json_decode($data, TRUE, 512, JSON_THROW_ON_ERROR);
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

    protected function getProviderDeck(int|string $shipId): array
    {
        // TODO Переделать выборку! shipId - это наш id.
        $providerDeck = Yii::$app->db->createCommand(
            'SELECT * FROM provider_deck WHERE ship_id = :ship_id AND provider_name = :provider_name',
            [
                ':provider_name' => self::PROVIDER_NAME,
                ':ship_id'       => $shipId
            ]
        )->queryAll();

        return ArrayHelper::index($providerDeck, 'foreign_id');
    }

    protected function getProviderCabin(int|string $shipId): array
    {
        $providerCabin = Yii::$app->db->createCommand(
            'SELECT * FROM provider_cabin WHERE ship_id = :ship_id AND provider_name = :provider_name',
            [
                ':provider_name' => self::PROVIDER_NAME,
                ':ship_id'       => $shipId
            ]
        )->queryAll();

        return ArrayHelper::index($providerCabin, 'foreign_id');
    }

    protected function getProviderCabinType( $shipId): array
    {
        $providerCabin = Yii::$app->db->createCommand(
            'SELECT * FROM provider_cabin_type WHERE ship_id = :ship_id AND provider_name = :provider_name',
            [
                ':provider_name' => self::PROVIDER_NAME,
                ':ship_id'       => $shipId
            ]
        )->queryAll();

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
        $loadPath = Yii::getAlias('@staticPublic') . $dir;

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

        try {
            $absolutePath = $this->getLoadPath($path);

            $filesInfo = pathinfo($file);

            $fileName = md5($filesInfo['filename']) . '.' . $filesInfo['extension'];

            copy($file, $absolutePath . $fileName);

            return '/public/' . $path . '/' . $fileName;

        } catch (Exception $e) {
            return '';
        }
    }

    protected function clearText($text): array|string
    {
        if (empty($text)) {
            return '';
        }
        return str_replace(['&nbsp;', '\n', PHP_EOL], '', $text);
    }

}