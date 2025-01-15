<?php include_once('includes/head.php') ?>
<style>
    .col-lg-8 {
        transition: all 0.3s ease;
    }

    .col-lg-4 {
        transition: all 0.3s ease;
    }

    @media (max-width: 991px) {
        .col-lg-12 {
            width: 100%;
        }
    }
</style>

<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
        <?php include_once('includes/header.php') ?>
        <!-- /Header -->

        <div class="page-wrapper pos-pg-wrapper ms-0">
            <div class="content pos-design p-0">
                <div class="row align-items-start pos-wrapper">
                    <div class="col-lg-8 product-right-block">
                        <div class="pos-categories tabs_wrapper">
                            <h5>Categories</h5>
                            <p>Select From Below Categories</p>
                            <ul class="tabs pos-category" style="display: flex; flex-direction:row; gap:15px; margin-bottom:12px; overflow-x:scroll" id="categories-container">
                                <!-- Categories view -->
                            </ul>
                            <div class="pos-products">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="mb-5 mt-5">PRODUCTS</h5>
                                </div>
                                <div class="tabs_container" id="tabs-container">
                                    <div class="tab_content active" data-tab="all">
                                        <div class="row">

                                            <!-- Products View -->
                                            <?php
                                            include('includes/product-skeleton.php');
                                            include('includes/product-skeleton.php');
                                            include('includes/product-skeleton.php');
                                            include('includes/product-skeleton.php');
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 ps-0 order-right-block">
                        <aside class="product-order-list">
                            <div class="head d-flex align-items-center justify-content-between w-100">
                                <div class="">
                                    <h5>New Order</h5>
                                </div>
                                <div class="">
                                    <a onclick="clearCart()" href="javascript:void(0);"><i data-feather="trash-2" class="feather-16 text-danger"></i></a>
                                </div>
                            </div>
                            <div class="customer-info block-section">
                                <h6>Customer Information</h6>
                                <div class="input-block d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" id="customer-name" name="customer-name">
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="btn btn-primary btn-icon"><i data-feather="user-plus" class="feather-16"></i></a>
                                </div>
                                <div class="input-block d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="form-group">
                                            <label>Contact</label>
                                            <input type="text" id="customer-contact" name="customer-contact">
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="btn btn-primary btn-icon"><i data-feather="phone" class="feather-16"></i></a>
                                </div>
                            </div>

                            <div class="product-added block-section">
                                <div class="head-text d-flex align-items-center justify-content-between">
                                    <h6 class="d-flex align-items-center mb-0">Products Added => <span class="count-items"> </span></h6>
                                    <a onclick="clearCart()" href="javascript:void(0);" class="d-flex align-items-center text-danger"><span class="me-1"><i data-feather="x" class="feather-16"></i></span>Clear all</a>
                                </div>
                                <div class="product-wrap">
                                </div>

                                <div class="card-body pt-0 pb-2">
                                    <div class="setvalue">
                                        <ul>
                                            <li>
                                                <h5>Subtotal </h5>
                                                <h6 class="cart-subtotal"></h6>
                                            </li>
                                            <li>
                                                <h5>Items total </h5>
                                                <h6 class="cart-total-items">4</h6>
                                            </li>
                                            <li class="total-value">
                                                <h5>Total </h5>
                                                <h6 class="cart-total-amount"></h6>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid btn-block ">
                                <a class="btn btn-secondary order-btn-container" href="javascript:void(0);">
                                    Oder Now : <span class="cart-total-checkout"></span>
                                </a>
                            </div>

                        </aside>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /Main Wrapper -->


    <?php include_once('includes/scripts.php') ?>