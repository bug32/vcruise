<?php

namespace common\components\image;

use Imagick;
use Yii;
use yii\base\Component;

class ImageComponent extends Component
{

    public const FORMAT_JPEG = 'jpeg';
    public const FORMAT_JPG = 'jpg';
    public const FORMAT_PNG = 'png';

    public const FORMAT_GIF = 'gif';
    public const FORMAT_BMP = 'bmp';
    public const FORMAT_XBM = 'xbm';
    public const FORMAT_SVG = 'svg';
    public const FORMAT_WEBP = 'webp';


    protected static array $formats
        = [
            'gif'  => 'image/gif',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'bmp'  => 'image/bmp',
            'xbm'  => 'image/xbm',
        ];

    public static string $dirMode = '0755';

    private static function getImageNameWebp($name): string
    {
        return preg_replace("/\.\w+$/", '.webp', $name);
    }

    /**
     * @param string $path      ссылка на файл
     * @param string $directory путь для сохранения файла
     *
     * @return string
     */
    public static function saveFile(string $path, string $directory = ''): string
    {
        if (empty($path)) {
            return '';
        }

        $fileExtension = pathinfo($path, PATHINFO_EXTENSION);
        $fileExtension = strtolower($fileExtension);
        if ($fileExtension === self::FORMAT_JPEG) {
            $fileExtension = self::FORMAT_JPG;
        }

        // Paths
        $thumbnailFileName = md5($path . $fileExtension) . '.' . $fileExtension;
        $directory         = (!empty($directory)) ? $directory : substr($thumbnailFileName, 0, 2);
        $thumbnailDir      = Yii::getAlias('@staticPublic') . DIRECTORY_SEPARATOR . $directory;
        $thumbnailPath     = '/public/' . $directory . DIRECTORY_SEPARATOR . $thumbnailFileName;


        if (!mkdir($thumbnailDir, static::$dirMode, TRUE) && !is_dir($thumbnailDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $thumbnailDir));
        }

        if ($fileExtension === self::FORMAT_SVG || $fileExtension === self::FORMAT_WEBP) {
            copy($path, $thumbnailDir . DIRECTORY_SEPARATOR . $thumbnailFileName);
            return $thumbnailPath;
        }

        $thumbnailFileName = self::getImageNameWebp(md5($path . $fileExtension) . '.' . $fileExtension);
        $thumbnailPath     = '/public/' . $directory . DIRECTORY_SEPARATOR . $thumbnailFileName;
        // Файл не подходит для конвертации в webp
        if (empty(self::$formats[$fileExtension])) {
            return '';
        }

        self::convertImageToWebp($path, Yii::getAlias('@static') . DIRECTORY_SEPARATOR . $thumbnailPath);

        return $thumbnailPath;
    }

    protected function getSavePath(string $directory): string
    {
        $loadPath = Yii::getAlias('@static') . '/public/' . $directory;

        if (!mkdir($loadPath, 0777, TRUE) && !is_dir($loadPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $loadPath));
        }


        return $loadPath . '/';
    }

    /**
     * Creates an image resource from a file based on its extension.
     *
     * @param string $file      The path to the image file.
     * @param string $extension The extension of the image file.
     *
     * @return resource|false The image resource, or false if creation fails.
     */
    protected static function createImageFromFile(string $file, string $extension)
    {
        switch ($extension) {
            case 'gif':
                return imagecreatefromgif($file);
            case 'jpg':
                return imagecreatefromjpeg($file);
            case 'png':
                $image = imagecreatefrompng($file);
                imagepalettetotruecolor($image);
                imagealphablending($image, TRUE);
                imagesavealpha($image, TRUE);
                return $image;
            case 'bmp':
                return imagecreatefrombmp($file);
            case 'xbm':
                return imagecreatefromxbm($file);
            default:
                return FALSE;
        }
    }

    public static function convertImageToWebp(string $sourceFile, string $directory): bool
    {
        $fileExtension = pathinfo($sourceFile, PATHINFO_EXTENSION);
        $fileExtension = strtolower($fileExtension);
        if ($fileExtension === self::FORMAT_JPEG) {
            $fileExtension = self::FORMAT_JPG;
        }

        // Файл не подходит для конвертации в webp
        if (empty(self::$formats[$fileExtension])) {
            return FALSE;
        }

        if (function_exists('imagewebp')) {
            $image = self::createImageFromFile($sourceFile, $fileExtension);
            if ($image === FALSE) {
                return '';
            }
            $result = imagewebp($image, $directory, 80);
            imagedestroy($image);

            return $result;
        }

        if (class_exists('Imagick')) {
            $image = new Imagick();
            try {
                $image->readImage($sourceFile);

                if ($fileExtension === self::FORMAT_PNG) {
                    $image->setImageFormat(self::FORMAT_WEBP);
                    $image->setImageCompressionQuality(80);
                    $image->setOption('webp:lossless', 'true');
                }
                $image->writeImage($directory);
                return TRUE;
            } catch (\ImagickException $e) {
                return FALSE;
            }
        }

        return TRUE;
    }
}