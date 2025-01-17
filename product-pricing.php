<?php
include_once('includes/head.php');
include_once('includes/auth.php');

requireAdmin()

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
            <h4>Product Sizes</h4>
            <h6>View Product Sizes</h6>
          </div>
          <div class="page-btn">
            <a href="javascript:void(0);" class="btn btn-adds" data-bs-toggle="modal"
              data-bs-target="#pricing-modal"><i class="fa fa-plus me-2"></i>Add Produt Pricing</a>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table  datanew">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Product Category</th>
                    <th>Product Size</th>
                    <th>Price (GHS)</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="product-pricing-content">

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="pricing-modal" tabindex="-1" aria-labelledby="create" aria-hidden="true">
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
                <h4>Product Size</h4>
                <h6>Create new size</h6>
              </div>
            </div>

            <div class="card">
              <div class="card-body">
                <form id="add-product-pricing-form" class="row" method="post">
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Category</label>
                      <select class="select" id="category-id" name="category-id">
                        <option value="Choose Category">Choose Category</option>
                        <?php
                        // Include database connection
                        include_once('includes/db_connection.php');

                        // Fetch categories from database
                        $query = "SELECT * FROM categories ORDER BY name";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                          echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-sm-6 col-12">
                    <div class="form-group">
                      <label>Size Name</label>
                      <select class="select" id="size-name" name="size-name">
                        <option value="Select">Select Size</option>
                        <option value="Medium">Medium</option>
                        <option value="Large">Large</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-sm-6 col-12">
                    <div class="form-group">
                      <label>Price (GHS) </label>
                      <input type="text" name="price" id="price">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
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