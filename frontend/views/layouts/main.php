<?php

/** @var \yii\web\View $this */

/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<?= $this->render('_head',[]); ?>

<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?= $this->render('_header', []); ?>

<main role="main" class="flex-shrink-0">
        <?php Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
</main>

<?= $this->render('_footer', []); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
