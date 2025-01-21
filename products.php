<?php
include_once('includes/head.php');
include_once('includes/auth.php');

requireAdmin();
?>

<body>
  <div id="global-loader">
    <div class="whirly-loader"> </div>
  </div>

  <div class="main-wrapper">

    <?php include_once('includes/header.php')  ?>

    <?php include_once('includes/sidebar.php') ?>

    <div class="page-wrapper">
      <div class="content">
        <div class="page-header">
          <div class="page-title">
            <h4>Product list</h4>
            <h6>View Products </h6>
          </div>
          <div class="page-btn">
            <a href="javascript:void(0);" class="btn btn-adds" data-bs-toggle="modal"
              data-bs-target="#create-product-modal">
              <i class="fa fa-plus me-2"></i>Add Product
            </a>
          </div>
        </div>

        <div class="card">
          <div class="card-body">

            <div class="table-responsive">
              <table class="table datanew" id="productsTable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Category name</th>
                    <th>Price (GHS)</th>
                    <th>Size</th>
                    <th>Created By</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="products-content">

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

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

  <div class="modal fade" id="edit-product-modal" tabindex="-1" aria-labelledby="create" aria-hidden="true">
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
                <h4>Edit Product</h4>
                <h6>Edit product details</h6>
              </div>
            </div>

            <div class="card">
              <div class="card-body">
                <form id="edit-product-form" enctype="multipart/form-data" method="post" class="row">
                  <div class="row">
                    <div class="col-lg-6 col-sm-6 col-12">
                      <div class="form-group">
                        <label>Product Name</label>
                        <input placeholder="Product Name" id="product-name-edt" name="product-name" type="text">
                      </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                      <div class="form-group">
                        <label>Category</label>
                        <input readonly type="text" id="product_category_id">
                      </div>
                    </div>

                    <div class="col-lg-6 col-sm-6 col-12">
                      <div class="form-group">
                        <label>Select Size</label>
                        <input readonly type="text" id="product_size">
                      </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                      <div class="form-group">
                        <label>Price (GHS)</label>
                        <input type="text" name="product-price" id="product-price">
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
                            <img style="width: 100px; height:100px; border-radius:50px; object-fit: contain; position: absolute; top:0; right:0;" id="product-image-preview" class="preview">
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

  
  <?php include_once('includes/scripts.php') ?>