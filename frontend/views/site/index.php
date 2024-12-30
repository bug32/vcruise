<?php

/** @var yii\web\View $this */

use frontend\components\View;
use frontend\widgets\HelpWidget\RequestSectionWidget;
use frontend\widgets\MainFilterWidget\MainFilterWidget;
use frontend\widgets\Offers\SeasonWidget;
use frontend\widgets\recommend\MainRecommendWidget;

$this->title = 'My Yii Application';


$this->registerJs(<<<JS

JS, View::POS_END);
?>

<?= MainFilterWidget::widget([]) ?>

<?= SeasonWidget::widget([]) ?>

<?= RequestSectionWidget::widget([]) ?>

<?= MainRecommendWidget::widget() ?>

<?= \frontend\widgets\HelpWidget\RequestFormWidget::widget([]) ?>
