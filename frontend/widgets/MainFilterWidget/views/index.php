<?php

/** @var View $this */

use frontend\components\View;

?>
<div class="section-main-filter position-relative">
    <div class="position-absolute w-100 bottom-1rem">
        <div class="container bg-light rounded-5 p-4">
            <div class="d-flex gap-4 align-items-center mb-4">
                <h4 class="m-0">
                    Поиск круиза
                </h4>
                <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-secondary" href="#">Круизы из Сочи</a>
                    <a class="btn btn-sm btn-secondary" href="#">Круизы по Египту</a>
                </div>
            </div>

            <form id="w111" class="" method="get" action="/">
                <div class="d-flex gap-3">
                    <div class="w-100">
                        <select class="form-select bg-light p-3" id="select-input">
                            <option>Дата отправления</option>
                            <option>Option item 1</option>
                            <option>Option item 2</option>
                            <option>Option item 3</option>
                        </select>
                    </div>
                    <div class="w-100">
                        <select class="form-select bg-light p-3" id="select-city">
                            <option>Город отправления</option>
                            <option>Option item 1</option>
                            <option>Option item 2</option>
                            <option>Option item 3</option>
                        </select>
                    </div>
                    <div class="w-100">
                        <select class="form-select bg-light p-3" id="select-input">
                            <option>Дней в круизе</option>
                            <option>Option item 1</option>
                            <option>Option item 2</option>
                            <option>Option item 3</option>
                        </select>
                    </div>
                    <div class="w-100">
                        <select class="form-select bg-light p-3" id="select-input">
                            <option>Теплоход</option>
                            <option>Option item 1</option>
                            <option>Option item 2</option>
                            <option>Option item 3</option>
                        </select>
                    </div>
                    <div class="ms-auto">
                        <button class="btn btn-success">Поиск</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
