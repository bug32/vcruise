<?php

/** @var View $this */

use frontend\components\View;

?>
<header class="fixed-top">
    <div class="header navbar navbar-expand-lg bg-light">
        <div class="container px-3">
            <a href="index.html" class="navbar-brand pe-3">
                <img src="assets/img/logo.svg" width="47" alt="Silicon">
                Silicon
            </a>

            <div class="form-check form-switch mode-switch pe-lg-1 ms-auto me-4" data-bs-toggle="mode">
                <input type="checkbox" class="form-check-input" id="theme-mode">
                <label class="form-check-label d-none d-sm-block" for="theme-mode">Light</label>
                <label class="form-check-label d-none d-sm-block" for="theme-mode">Dark</label>
            </div>
            <button type="button" class="navbar-toggler"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="https://themes.getbootstrap.com/product/silicon-business-technology-template-ui-kit/"
               class="btn btn-primary btn-sm fs-sm rounded d-none d-lg-inline-flex"
               target="_blank" rel="noopener">
                <i class="bx bx-cart fs-5 lh-1 me-1"></i>
                Заказать звонок
            </a>
        </div>
    </div>

    <div class="header navbar navbar-expand-lg bg-light">
        <div class="container">
            <div id="navbarNav" class="offcanvas offcanvas-fluid">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0  justify-content-center">
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown"
                               aria-current="page">Круизы</a>
                            <div class="dropdown-menu p-0">
                                <div class="d-lg-flex">
                                    <!--
                                    <div class="mega-dropdown-column d-flex justify-content-center align-items-center rounded-3 rounded-end-0 px-0"
                                         style="margin: -1px; background-color: #f3f6ff;">
                                        <img src="/image/landings.jpg" alt="Landings">
                                    </div>
                                    -->
                                    <div class="mega-dropdown-column pt-lg-3 pb-lg-4"
                                         style="--si-mega-dropdown-column-width: 15rem;">
                                        <ul class="list-unstyled mb-0">
                                            <li>
                                                <a href="index.html" class="dropdown-item">
                                                    Template Intro Page
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link ">
                                Теплоходы
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Account</a>
                            <ul class="dropdown-menu">
                                <li><a href="account-details.html" class="dropdown-item">Account Details</a></li>
                                <li><a href="account-security.html" class="dropdown-item">Security</a></li>
                                <li><a href="account-notifications.html" class="dropdown-item">Notifications</a></li>
                                <li><a href="account-messages.html" class="dropdown-item">Messages</a></li>
                                <li><a href="account-saved-items.html" class="dropdown-item">Saved Items</a></li>
                                <li><a href="account-collections.html" class="dropdown-item">My Collections</a></li>
                                <li><a href="account-payment.html" class="dropdown-item">Payment Details</a></li>
                                <li><a href="account-signin.html" class="dropdown-item">Sign In</a></li>
                                <li><a href="account-signup.html" class="dropdown-item">Sign Up</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="components/typography.html" class="nav-link">UI Kit</a>
                        </li>
                        <li class="nav-item">
                            <a href="docs/getting-started.html" class="nav-link">Docs</a>
                        </li>
                    </ul>
                </div>
                <div class="offcanvas-header border-top">
                    <a href="https://themes.getbootstrap.com/product/silicon-business-technology-template-ui-kit/"
                       class="btn btn-primary w-100"
                       target="_blank" rel="noopener">
                        <i class="bx bx-cart fs-4 lh-1 me-1"></i>
                        Buy now
                    </a>
                </div>
            </div>
        </div>
    </div>

</header>



