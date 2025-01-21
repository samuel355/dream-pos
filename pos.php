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

    <!-- <div>
        <select onchange=changeBobaSize(${
            item.cart_id
            }) style="margin-left: 2px; margin-right: 2px" class="select" id="change-boba-size" name="change-boba-size">
            <option value="Change Size">Change Size</option>
            <option value="Medium">Medium</option>
            <option value="Large">Large</option>
        </select>
    </div> -->

    <div class="header">
        <div class="page-header">
            <div class="page-btn mt-3 mb-5">
                <a href="/dashboard" class="btn btn-added">
                    Go To Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

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
                                            // include('includes/product-skeleton.php');
                                            // include('includes/product-skeleton.php');
                                            // include('includes/product-skeleton.php');
                                            // include('includes/product-skeleton.php');
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

    <div class="modal fade" id="create-product-modal" tabindex="-1" aria-labelledby="create" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <div class="page-header">
                            <div class="page-title">
                                <h4>Add Product</h4>
                                <h6>Create New Product</h6>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <form id="add-product-form" enctype="multipart/form-data" method="post" class="row">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Product Name</label>
                                                <input placeholder="Product Name" id="product-name" name="product-name" type="text">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Change Category</label>
                                                <select class="select" id="new-category-id" name="new-category-id">
                                                    <option value="Choose Category">Choose Category</option>
                                                    <?php
                                                    // Include database connection
                                                    include_once('includes/db_connection.php');

                                                    // Fetch categories from database
                                                    $query = "SELECT * FROM categories ORDER BY id";
                                                    $result = mysqli_query($conn, $query);

                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Change Size</label>
                                                <select class="select" id="new-size" name="new-size">
                                                    <option value="Select Size">Select Size</option>
                                                    <option value="Medium">Medium</option>
                                                    <option value="Large">Large</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Price (GHS)</label>
                                                <input placeholder="Price (GHS)" type="text" name="price" id="price">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-12">
                                            <div class="form-group">
                                                <label> Product Image</label>
                                                <div class="image-upload">
                                                    <input type="file"
                                                        id="image"
                                                        name="image"
                                                        accept="image/*"
                                                        onchange="previewImage(this);">
                                                    <div class="image-uploads">
                                                        <img src="assets/img/icons/upload.svg" alt="img">
                                                        <h4>Drag and drop a file to upload</h4>
                                                        <img style="width: 100px; height:100px; border-radius:50px; object-fit: contain; position: absolute; top:0; right:0;" id="preview" class="preview-image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-submit me-2">Add Product</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="change-boba-size-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Size</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="cart_id_input">
                    <select id="boba-new-size" class="form-select">
                        <option value="Choose Size">Choose Size</option>
                        <option value="Medium">Medium</option>
                        <option value="Large">Large</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateSize()">Update Size</button>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/scripts.php') ?>

    <script>
        document.getElementById('customer-contact').addEventListener('keydown', allowOnlyNumbers);
    </script>