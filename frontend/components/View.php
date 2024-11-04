<?php

namespace frontend\components;

use frontend\helpers\Html;
use frontend\helpers\Url;
use Yii;
use yii\helpers\ArrayHelper;

class View extends \rmrevin\yii\minify\View
{

    /**
     * @var bool показывать header
     */
    public bool $header = true;
    /**
     * @var bool показывать sidebar
     */
    public bool $aside = true;
    /**
     * @var bool свернуть sidebar
     */
    public bool $asideCollapsed = true;
    /**
     * @var bool показывать footer
     */
    public bool $footer = true;
    /**
     * @var null|string description мета тэг
     */
    public ?string $description = '';
    /**
     * @var null|string keywords мета тэг
     */
    public ?string $keywords = '';
    /**
     * @var array хлебные крошки
     */
    public array $breadcrumbs = [];

    /**
     * @return void
     * @throws \Exception
     */
    public function registerDefaultTag(): void
    {
        $this->description = Html::encode($this->description);
        $this->keywords = Html::encode($this->keywords);

        $this->registerMetaTag(['name' => 'description', 'content' => $this->description]);
        $this->registerMetaTag(['name' => 'keywords', 'content' => $this->keywords]);


        $this->registerMetaTag(['charset' => Yii::$app->charset]);
        $this->registerMetaTag(['http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge']);
        $this->registerMetaTag(['name' => 'mobile-web-app-capable', 'content' => 'yes']);
        $this->registerMetaTag(['name' => 'apple-mobile-web-app-capable', 'content' => 'yes']);
        $this->registerMetaTag(['name' => 'msapplication-starturl', 'content' => '/']);
        $this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=5.0, user-scalable=yes, shrink-to-fit=no']);

        $this->registerMetaTag(['name' => 'application-name', 'content' => Yii::$app->name]);
        $this->registerMetaTag(['name' => 'apple-mobile-web-app-title', 'content' => Yii::$app->name]);

        $metaTags = $this->metaTags;

        if (!array_key_exists('ogTitle', $metaTags)) {
            $this->registerOgTitle(ArrayHelper::getValue($this, 'title', Yii::$app->name));
        }

        if (!array_key_exists('ogDescription', $metaTags)) {
            $this->registerOgDescription($this->description);
        }

        if (!array_key_exists('ogSiteName', $metaTags)) {
            $this->registerOgSiteName(Yii::$app->name);
        }

        if (!array_key_exists('ogUrl', $metaTags)) {
            $this->registerOgUrl(Url::current(scheme: true));
        }

        if (!array_key_exists('ogType', $metaTags)) {
            $this->registerOgType('website');
        }

        if (!array_key_exists('ogImage', $metaTags)) {
            $this->registerOgImage(View::getHeadImg('maskable_icon_x512.png'), false);
        }

        $this->registerLinkTag(['rel' => 'shortcut icon', 'href' => View::getHeadImg('favicon.ico'), 'type' => 'image/x-icon']);
        $this->registerLinkTag(['rel' => 'icon', 'href' => View::getHeadImg('favicon-16x16.png'), 'size' => '16x16', 'type' => 'image/png']);
        $this->registerLinkTag(['rel' => 'icon', 'href' => View::getHeadImg('favicon-32x32.png'), 'size' => '32x32', 'type' => 'image/png']);
        $this->registerLinkTag(['rel' => 'apple-touch-icon', 'href' => View::getHeadImg('apple-touch-icon.png'), 'sizes' => '180x180']);

    }

    /**
     * Помощник подключения изображений в секции <head>
     * @param string $fileName имя файла
     * * @param string $path путь до папки с файлом
     * @return string
     */
    public static function getHeadImg(string $fileName, string $path = '@image/layout/head/'): string
    {
        return Url::base(true) . Yii::getAlias($path . $fileName);
    }

    /**
     * Open Graph Types
     * @param string|null $content
     * @return void
     * @see https://ogp.me/#types
     */
    public function registerOgType(?string $content = ''): void
    {
        if (!empty($content)) {
            $this->registerMetaTag(['name' => 'og:type', 'content' => $content], 'ogType');
        }
    }

    /**
     * @param string|null $content
     * @return void
     * @see https://ogp.me/#metadata
     */
    public function registerOgTitle(?string $content = ''): void
    {
        if (!empty($content)) {
            $this->registerMetaTag(['name' => 'og:title', 'content' => $content], 'ogTitle');
        }
    }

    /**
     * @param string|null $content
     * @return void
     * @see https://ogp.me/#optional
     */
    public function registerOgDescription(?string $content = ''): void
    {
        if (!empty($content)) {
            $this->registerMetaTag(['name' => 'og:description', 'content' => $content], 'ogDescription');
        }
    }

    /**
     * @param string|null $content
     * @return void
     * @see https://ogp.me/#metadata
     */
    public function registerOgUrl(?string $content = ''): void
    {
        if (!empty($content)) {
            $this->registerMetaTag(['name' => 'og:url', 'content' => $content], 'ogUrl');
        }
    }

    /**
     * @param string|null $url
     * @param bool $scheme
     * @return void
     * @see https://ogp.me/#structured
     */
    public function registerOgImage(?string $url = '', bool $scheme = true): void
    {
        if (!empty($url)) {
            if ($scheme) {
                $url = Url::base(true) . $url;
            }

            $this->registerMetaTag(['name' => 'og:image', 'content' => $url], 'ogImage');
        }
    }

    /**
     * @param string|null $content
     * @return void
     * @see https://ogp.me/#optional
     */
    public function registerOgSiteName(?string $content = ''): void
    {
        if (!empty($content)) {
            $this->registerMetaTag(['name' => 'og:site_name', 'content' => $content], 'ogSiteName');
        }
    }


}
