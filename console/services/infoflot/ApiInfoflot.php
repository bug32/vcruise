<?php

namespace console\services\infoflot;

use Yii;
use yii\web\UrlNormalizer;

abstract class ApiInfoflot {

    const BASE_URL = 'https://restapi.infoflot.com';
    const TOKEN = "ddf71b33662078a069d2a1eacafefb33cc783bf6";
    const PAGE_LIMIT = 50;

    // функция получения данных по URL и преобразование в объект
    public function request(string $url) {

        //$url = self::BASE_URL . $url . '?limit=' . self::PAGE_LIMIT . '&key' . self::TOKEN;

        $url = self::BASE_URL .$url;
        $normalize = new UrlNormalizer();
        $url = $normalize->normalizePathInfo($url, '');

        if (empty($url)) {
            return [];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = curl_exec($ch);
        curl_close($ch);

        return json_decode($data, TRUE);
    }

    /** Сохранение картинок в папке image
     * @param string $dir // каталог для сохранения файла
     * @return string
     */
    protected function getLoadPath(string $dir): string
    {
        $loadPath = Yii::getAlias('@frontend') . '/web/public/' . $dir;

        if (!file_exists($loadPath)) {
            if (!mkdir($loadPath, 0777, TRUE) && !is_dir($loadPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $loadPath));
            }
        }

        return $loadPath . '/';
    }

    /**
     *  Загрузка файла по ссылке $file в папку frontend/web/images/$path
     * @param $file
     * @param $path
     * @return string
     */
    protected function loadFile($file, $path): string
    {
        if (empty($file)) {
            return '';
        }
        $absolutePath = $this->getLoadPath($path);

        $fileName = basename($file);
        $files = explode('.', $fileName);
        if (count($files)!=2){
            return '';
        }
        $fileName = md5($files[0]) . '.' . $files[1];

        copy($file, $absolutePath . $fileName);

        return '/public/' . $path .'/'. $fileName;
    }

    protected function clearText($text)
    {
        if(empty($text)){
            return '';
        }
        return str_replace(['&nbsp;'], '', $text);
    }
}
