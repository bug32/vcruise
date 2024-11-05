<?php
/** @var View $this */

use frontend\components\View;
use frontend\helpers\Html;

echo Html::beginTag('head');
echo Html::tag('title', Html::encode($this->title));
echo Html::csrfMetaTags();

$this->registerDefaultTag();
$this->registerMetaTag(['name' => 'author', 'content' => 'РТК ИТ']);
$this->registerMetaTag(['name' => 'theme-color', 'content' => '#ffffff']);
$this->registerMetaTag(['name' => 'apple-mobile-web-app-status-bar-style', 'content' => '#ffffff']);
$this->registerLinkTag(['rel' => 'manifest', 'href' => '/manifest.json']);

// $this->registerMetaTag(['name' => 'yandex-verification', 'content' => 'f78720e86097a21e']);

$this->head();?>

<script>
    (function () {
        window.onload = function () {
            const preloader = document.querySelector('.page-loading');
            preloader.classList.remove('active');
            setTimeout(function () {
               // preloader.remove();
            }, 1000);
        };
    })();
</script>

<?php echo Html::endTag('head');
