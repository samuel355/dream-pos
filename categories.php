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
            <h4>Product Category list</h4>
            <h6>View/Search product Category</h6>
          </div>
          <div class="page-btn">
            <a href="javascript:void(0);" class="btn btn-adds" data-bs-toggle="modal"
              data-bs-target="#create-modal">
              <i class="fa fa-plus me-2"></i>Add Category
            </a>
          </div>
        </div>

        <div class="card">
          <div class="card-body">

            <div class="table-responsive">
              <table class="table  datanew">
                <thead>
                  <tr>
                    <th>Image</th>
                    <th>Category name</th>
                    <th>Created By</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="categories-content">

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="create-modal" tabindex="-1" aria-labelledby="create" aria-hidden="true">
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
    </div>
  </div>

  <div class="modal fade" id="edit-category-modal" tabindex="-1" aria-labelledby="create" aria-hidden="true">
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
                <h4>Product Edit Category</h4>
                <h6>Edit product Category</h6>
              </div>
            </div>

            <div class="card">
              <div class="card-body">
                <form id="edit-category-form" class="row" enctype="multipart/form-data" method="post">
                  <div class="col-lg-6 col-sm-6 col-12">
                    <div class="form-group">
                      <label>Category Name</label>
                      <input type="text" name="category-name" id="category-name-edt">
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
                          <h4>Drag and drop an image file to change the existing image</h4>
                          <img style="width: 100px; height:100px; border-radius:50px; object-fit: contain; position: absolute; top:0; right:0;" id="cat-preview" class="preview-image">
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
    </div>
  </div>

  <?php include_once('includes/scripts.php') ?>