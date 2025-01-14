<?php include_once('includes/head.php') ?>

<body>
  <div id="global-loader">
    <div class="whirly-loader"> </div>
  </div>

  <div class="main-wrapper">

    <?php include_once('includes/header.php') ?>

    <?php include_once('includes/sidebar.php') ?>

    <div class="page-wrapper">
      <div class="content">
        <div class="page-header">
          <div class="page-title">
            <h4>Product Add</h4>
            <h6>Create new product</h6>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <form id="add-product-form" enctype="multipart/form-data" method="post" class="row">
              <div class="row">
                <div class="col-lg-4 col-sm-6 col-12">
                  <div class="form-group">
                    <label>Product Name</label>
                    <input id="product-name" name="product-name" type="text">
                  </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-12">
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
                    <label>Price</label>
                    <input type="text" name="price" id="price">
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
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
                <button type="submit" class="btn btn-submit me-2">Submit</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>


  <?php include_once('includes/scripts.php') ?>