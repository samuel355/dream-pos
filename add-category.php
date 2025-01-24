<?php include 'includes/head.php' ?>

<body>
  <div id="global-loader">
    <div class="whirly-loader"> </div>
  </div>

  <div class="main-wrapper">

    <?php include 'includes/header.php'  ?>

    <?php include 'includes/sidebar.php' ?>

    <div class="page-wrapper">
      <div class="content">
        <div class="page-header">
          <div class="page-title">
            <h4>Product Add Category</h4>
            <h6>Create new product Category</h6>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <form id="add-category-form" class="row" enctype="multipart/form-data" method="post">
              <div class="col-lg-6 col-sm-6 col-12">
                <div class="form-group">
                  <label>Category Name</label>
                  <input type="text" name="category-name" id="category-name">
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

  <?php include 'includes/scripts.php' ?>
  </body>

</html>