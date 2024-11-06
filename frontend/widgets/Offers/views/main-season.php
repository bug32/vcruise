<?php
/** @var View $this */
/** @var SeasonWidget $widget */

/** @var int $id */

use frontend\assets\SwiperAsset;
use frontend\components\View;
use frontend\widgets\Offers\SeasonWidget;

SwiperAsset::register($this);
?>
<div class="section offers-season">
    <!-- Multiple slides responsive slider with external Prev / Next buttons and bullets outside -->
    <div class="position-relative px-xl-5">

        <!-- Slider prev/next buttons -->
        <button type="button" id="prev-news"
                class="btn btn-prev btn-icon btn-sm position-absolute top-50 start-0 translate-middle-y d-none d-xl-inline-flex"
                aria-label="Previous">
            <i class="bx bx-chevron-left"></i>
        </button>
        <button type="button" id="next-news"
                class="btn btn-next btn-icon btn-sm position-absolute top-50 end-0 translate-middle-y d-none d-xl-inline-flex"
                aria-label="Next">
            <i class="bx bx-chevron-right"></i>
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
                                <a href="#" class="position-absolute top-0 start-0 w-100 h-100"
                                   aria-label="Read more"></a>
                                <a href="#"
                                   class="btn btn-icon btn-light bg-white border-white btn-sm rounded-circle position-absolute top-0 end-0 zindex-5 me-3 mt-3"
                                   data-bs-toggle="tooltip" data-bs-placement="left" title="Read later"
                                   aria-label="Read later">
                                    <i class="bx bx-bookmark"></i>
                                </a>
                                <img src="assets/img/landing/financial/news/01.jpg" class="card-img-top" alt="Image">
                            </div>
                            <div class="card-body pb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <a href="#"
                                       class="badge fs-sm text-nav bg-secondary text-decoration-none">Business</a>
                                    <span class="fs-sm text-muted">12 hours ago</span>
                                </div>
                                <h3 class="h5 mb-0">
                                    <a href="#">How agile is your forecasting process?</a>
                                </h3>
                            </div>
                            <div class="card-footer py-4">
                                <a href="#" class="d-flex align-items-center text-decoration-none">
                                    <img src="assets/img/avatar/40.jpg" class="rounded-circle" width="48" alt="Avatar">
                                    <div class="ps-3">
                                        <h6 class="fs-base fw-semibold mb-0">Marvin McKinney</h6>
                                        <span class="fs-sm text-muted">Deputy Director, Capital Department</span>
                                    </div>
                                </a>
                            </div>
                        </article>
                    </div>

                    <!-- Item -->
                    <div class="swiper-slide h-auto pb-3">
                        <article class="card h-100 border-0 shadow-sm mx-2">
                            <div class="position-relative">
                                <a href="#" class="position-absolute top-0 start-0 w-100 h-100"
                                   aria-label="Read more"></a>
                                <a href="#"
                                   class="btn btn-icon btn-light bg-white border-white btn-sm rounded-circle position-absolute top-0 end-0 zindex-5 me-3 mt-3"
                                   data-bs-toggle="tooltip" data-bs-placement="left" title="Read later"
                                   aria-label="Read later">
                                    <i class="bx bx-bookmark"></i>
                                </a>
                                <img src="assets/img/landing/financial/news/02.jpg" class="card-img-top" alt="Image">
                            </div>
                            <div class="card-body pb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <a href="#" class="badge fs-sm text-nav bg-secondary text-decoration-none">Enterprise</a>
                                    <span class="fs-sm text-muted">1 day ago</span>
                                </div>
                                <h3 class="h5 mb-0">
                                    <a href="#">A future with high public debt: low-for-long is not low forever</a>
                                </h3>
                            </div>
                            <div class="card-footer py-4">
                                <a href="#" class="d-flex align-items-center text-decoration-none">
                                    <img src="assets/img/avatar/04.jpg" class="rounded-circle" width="48" alt="Avatar">
                                    <div class="ps-3">
                                        <h6 class="fs-base fw-semibold mb-0">Jenny Wilson</h6>
                                        <span class="fs-sm text-muted">Financial Sector Expert</span>
                                    </div>
                                </a>
                            </div>
                        </article>
                    </div>

                    <!-- Item -->
                    <div class="swiper-slide h-auto pb-3">
                        <article class="card h-100 border-0 shadow-sm mx-2">
                            <div class="position-relative">
                                <a href="#" class="position-absolute top-0 start-0 w-100 h-100"
                                   aria-label="Read more"></a>
                                <a href="#"
                                   class="btn btn-icon btn-light bg-white border-white btn-sm rounded-circle position-absolute top-0 end-0 zindex-5 me-3 mt-3"
                                   data-bs-toggle="tooltip" data-bs-placement="left" title="Read later"
                                   aria-label="Read later">
                                    <i class="bx bx-bookmark"></i>
                                </a>
                                <img src="assets/img/landing/financial/news/03.jpg" class="card-img-top" alt="Image">
                            </div>
                            <div class="card-body pb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <a href="#"
                                       class="badge fs-sm text-nav bg-secondary text-decoration-none">Finance</a>
                                    <span class="fs-sm text-muted">Nov 24, 2023</span>
                                </div>
                                <h3 class="h5 mb-0">
                                    <a href="#">Understanding the rise in long-term rates</a>
                                </h3>
                            </div>
                            <div class="card-footer py-4">
                                <a href="#" class="d-flex align-items-center text-decoration-none">
                                    <img src="assets/img/avatar/05.jpg" class="rounded-circle" width="48" alt="Avatar">
                                    <div class="ps-3">
                                        <h6 class="fs-base fw-semibold mb-0">Albert Flores</h6>
                                        <span class="fs-sm text-muted">Financial Counsellor and Director</span>
                                    </div>
                                </a>
                            </div>
                        </article>
                    </div>

                    <!-- Item -->
                    <div class="swiper-slide h-auto pb-3">
                        <article class="card h-100 border-0 shadow-sm mx-2">
                            <div class="position-relative">
                                <a href="#" class="position-absolute top-0 start-0 w-100 h-100"
                                   aria-label="Read more"></a>
                                <a href="#"
                                   class="btn btn-icon btn-light bg-white border-white btn-sm rounded-circle position-absolute top-0 end-0 zindex-5 me-3 mt-3"
                                   data-bs-toggle="tooltip" data-bs-placement="left" title="Read later"
                                   aria-label="Read later">
                                    <i class="bx bx-bookmark"></i>
                                </a>
                                <img src="assets/img/landing/financial/news/04.jpg" class="card-img-top" alt="Image">
                            </div>
                            <div class="card-body pb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <a href="#" class="badge fs-sm text-nav bg-secondary text-decoration-none">Ticks &
                                        Trips</a>
                                    <span class="fs-sm text-muted">Oct 13, 2023</span>
                                </div>
                                <h3 class="h5 mb-0">
                                    <a href="#">Stocks making the biggest moves after hours</a>
                                </h3>
                            </div>
                            <div class="card-footer py-4">
                                <a href="#" class="d-flex align-items-center text-decoration-none">
                                    <img src="assets/img/avatar/41.jpg" class="rounded-circle" width="48" alt="Avatar">
                                    <div class="ps-3">
                                        <h6 class="fs-base fw-semibold mb-0">Jerome Bell</h6>
                                        <span class="fs-sm text-muted">Business Analyst</span>
                                    </div>
                                </a>
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
