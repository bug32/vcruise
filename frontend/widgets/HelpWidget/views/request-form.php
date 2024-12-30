<?php

/** @var View $this */

/** @var RequestCellForm $model */

use frontend\components\View;
use frontend\helpers\Url;
use frontend\models\forms\RequestCellForm;
use yii\bootstrap5\ActiveForm;

?>
<div class="section request-form">
    <div class="container bg-request-form">
        <div class="row justify-content-start align-items-center h-100">
            <div class="col-12 col-md-6 h-100 d-flex flex-column justify-content-center">
                <div class="head fw-500">
                    <h2>Остались вопросы?</h2>
                </div>
                <?php $form = ActiveForm::begin([
                    'id'     => 'request-cell-form',
                    'method' => 'post',
                    'action' => Url::toRoute(['/'])
                ]) ?>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <?= $form->field($model, 'name')->textInput() ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?= $form->field($model, 'phone')->textInput() ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?= $form->field($model, 'check')->checkbox() ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?= \frontend\helpers\Html::submitButton(' Заказать звонок <i class="ps-3 fa-solid fa-arrow-right"></i>',[
                                'class' => 'btn btn-xl btn-success w-100'
                        ]) ?>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
