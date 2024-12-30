<?php
/** @var View $this */
/** @var SeasonWidget $widget */

/** @var int $id */

use frontend\assets\SwiperAsset;
use frontend\components\View;
use frontend\widgets\Offers\SeasonWidget;

SwiperAsset::register($this);
?>
<div class="section main-recommend">
    <div class="container">
        <div class="head pb-4">
            <h2 class="text-center fw-500">Мы рекомендуем</h2>
        </div>
        <!-- Multiple slides responsive slider with external Prev / Next buttons and bullets outside -->
        <div class="position-relative px-xl-5">

            <!-- Slider prev/next buttons -->
            <button type="button" id="prev-news"
                    class="btn btn-prev btn-icon btn-sm position-absolute top-50 start-0 translate-middle-y d-none d-xl-inline-flex"
                    aria-label="Previous">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button type="button" id="next-news"
                    class="btn btn-next btn-icon btn-sm position-absolute top-50 end-0 translate-middle-y d-none d-xl-inline-flex"
                    aria-label="Next">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <!-- Slider -->
            <div class="px-xl-2">
                <div class="swiper mx-n2" data-swiper-options='{
                  "slidesPerView": 1,
                  "loop": true,
                  "pagination": {
                    "el": ".swiper-pagination",
                    "clickable": true
                  },
                  "navigation": {
                    "prevEl": "#prev-news",
                    "nextEl": "#next-news"
                  },
                  "breakpoints": {
                    "500": {
                      "slidesPerView": 2
                    },
                    "1000": {
                      "slidesPerView": 3
                    }
                  }
                }'>
                    <div class="swiper-wrapper">

                        <!-- Item -->
                        <div class="swiper-slide h-auto pb-3">
                            <article class="card h-100 border-0 shadow-sm mx-2">
                                <div class="position-relative">
                                    <img src="/image/news-1.png" class="card-img-top" alt="Image">
                                </div>
                                <div class="card-body pb-4">
                                    <h3 class="h5 mb-0">
                                        <a href="#">How agile is your forecasting process?</a>
                                    </h3>
                                    <span class="fs-sm text-muted">Deputy Director, Capital Department</span>
                                </div>
                            </article>
                        </div>

                        <!-- Item -->
                        <div class="swiper-slide h-auto pb-3">
                            <article class="card h-100 border-0 shadow-sm mx-2">
                                <div class="position-relative">
                                    <img src="/image/news-1.png" class="card-img-top" alt="Image">
                                </div>
                                <div class="card-body pb-4">
                                    <h3 class="h5 mb-0">
                                        <a href="#">How agile is your forecasting process?</a>
                                    </h3>
                                    <span class="fs-sm text-muted">Deputy Director, Capital Department</span>
                                </div>
                            </article>
                        </div>

                        <!-- Item -->
                        <div class="swiper-slide h-auto pb-3">
                            <article class="card h-100 border-0 shadow-sm mx-2">
                                <div class="position-relative">
                                    <img src="/image/news-1.png" class="card-img-top" alt="Image">
                                </div>
                                <div class="card-body pb-4">
                                    <h3 class="h5 mb-0">
                                        <a href="#">How agile is your forecasting process?</a>
                                    </h3>
                                    <span class="fs-sm text-muted">Deputy Director, Capital Department</span>
                                </div>
                            </article>
                        </div>

                        <!-- Item -->
                        <div class="swiper-slide h-auto pb-3">
                            <article class="card h-100 border-0 shadow-sm mx-2">
                                <div class="position-relative">
                                    <img src="/image/news-1.png" class="card-img-top" alt="Image">
                                </div>
                                <div class="card-body pb-4">
                                    <h3 class="h5 mb-0">
                                        <a href="#">How agile is your forecasting process?</a>
                                    </h3>
                                    <span class="fs-sm text-muted">Deputy Director, Capital Department</span>
                                </div>
                            </article>
                        </div>

                        <!-- Item -->
                        <div class="swiper-slide h-auto pb-3">
                            <article class="card h-100 border-0 shadow-sm mx-2">
                                <div class="position-relative">
                                    <img src="/image/news-1.png" class="card-img-top" alt="Image">
                                </div>
                                <div class="card-body pb-4">
                                    <h3 class="h5 mb-0">
                                        <a href="#">How agile is your forecasting process?</a>
                                    </h3>
                                    <span class="fs-sm text-muted">Deputy Director, Capital Department</span>
                                </div>
                            </article>
                        </div>

                        <!-- Item -->
                        <div class="swiper-slide h-auto pb-3">
                            <article class="card h-100 border-0 shadow-sm mx-2">
                                <div class="position-relative">
                                    <img src="/image/news-1.png" class="card-img-top" alt="Image">
                                </div>
                                <div class="card-body pb-4">
                                    <h3 class="h5 mb-0">
                                        <a href="#">How agile is your forecasting process?</a>
                                    </h3>
                                    <span class="fs-sm text-muted">Deputy Director, Capital Department</span>
                                </div>
                            </article>
                        </div>

                        <!-- Item -->
                        <div class="swiper-slide h-auto pb-3">
                            <article class="card h-100 border-0 shadow-sm mx-2">
                                <div class="position-relative">
                                    <img src="/image/news-1.png" class="card-img-top" alt="Image">
                                </div>
                                <div class="card-body pb-4">
                                    <h3 class="h5 mb-0">
                                        <a href="#">How agile is your forecasting process?</a>
                                    </h3>
                                    <span class="fs-sm text-muted">Deputy Director, Capital Department</span>
                                </div>
                            </article>
                        </div>

                    </div>

                    <!-- Pagination (bullets) -->
                    <div class="swiper-pagination position-relative bottom-0 mt-4 mb-lg-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>
