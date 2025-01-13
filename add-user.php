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
            <h4>User Management</h4>
            <h6>Add/ User</h6>
          </div>
        </div>

        <div class="card">
          <form class="card-body" id="add-user-form" enctype="multipart/form-data" method="post">
            <div class="row">
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Full Name</label>
                  <input type="text" id="fullname" name="fullname">
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <div class="pass-group">
                    <input name="password" id="password" type="password" class=" pass-input">
                    <span class="fas toggle-password fa-eye-slash"></span>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Contact</label>
                  <input type="tel" id="phone" name="phone">
                </div>
                <div class="form-group">
                  <label>Role</label>
                  <select class="select" id="role" name="role">
                    <option value="Select">Select</option>
                    <option value="Cashier">Cashier</option>
                    <option value="Admin">Admin</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Confirm Password</label>
                  <div class="pass-group">
                    <input id="repassword" name="repassword" type="password" class=" pass-inputs">
                    <span class="fas toggle-passworda fa-eye-slash"></span>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12 ">
                <div class="form-group">
                  <label> Profile Picture</label>
                  <div class="image-upload image-upload-new">
                    <input type="file"
                      id="image"
                      name="image"
                      accept="image/*"
                      onchange="previewImage(this);"
                    >
                    <div class="image-uploads relative">
                      <img src="assets/img/icons/upload.svg" alt="img">
                      <h4>Drag and drop a file to upload</h4>
                      <img style="width: 100px; height:100px; border-radius:50px; object-fit: contain; position: absolute; top:0; right:0;" id="preview" class="preview-image">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <button type="submit" href="javascript:void(0);" class="btn btn-submit me-2">Submit</button>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>


  <?php include_once('includes/scripts.php') ?>