<?php
include_once('includes/head.php');
include_once('includes/auth.php');

requireAdmin();
?>

<body>
  <div id="global-loader">
    <div class="whirly-loader"></div>
  </div>

  <div class="main-wrapper">
    <?php include_once('includes/header.php') ?>
    <?php include_once('includes/sidebar.php') ?>

    <div class="page-wrapper">
      <div class="content">
        <div class="page-header">
          <div class="page-title">
            <h4>User List</h4>
            <h6>Manage Users</h6>
          </div>
          <div class="page-btn">
            <a href="javascript:void(0);" class="btn btn-adds" data-bs-toggle="modal" data-bs-target="#create-user-modal">
              <i class="fa fa-plus me-2"></i>Add User
            </a>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table datanew" id="usersTable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="users-content">
                  <!-- Users will be loaded here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Create User Modal -->
  <div class="modal fade" id="create-user-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create User</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="add-user-form" enctype="multipart/form-data">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Full Name<span class="text-danger">*</span></label>
                  <input type="text" name="fullname" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Email<span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Phone<span class="text-danger">*</span></label>
                  <input type="text" name="phone" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Role<span class="text-danger">*</span></label>
                  <select name="role" class="select" required>
                    <option value="admin">Admin</option>
                    <option value="cashier">Cashier</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Password<span class="text-danger">*</span></label>
                  <input type="password" name="password" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Confirm Password<span class="text-danger">*</span></label>
                  <input type="password" name="repassword" class="form-control" required>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Profile Image</label>
                  <div class="image-upload">
                    <input type="file" name="image" accept="image/*" onchange="previewImage(this);">
                    <div class="image-uploads">
                      <img src="assets/img/icons/upload.svg" alt="upload">
                      <h4>Drag and drop a file to upload</h4>
                      <img id="preview" style="width: 100px; height:100px; border-radius:50px; object-fit: contain; position: absolute; top:0; right:0;">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="submit-section">
              <button type="submit" class="btn btn-primary submit-btn">Add User</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit User Modal -->
  <div class="modal fade" id="edit-user-modal" tabindex="-1" aria-hidden="true">
    <!-- Similar to create modal but with pre-filled values -->
  </div>

  <?php include_once('includes/scripts.php') ?>
  <script src="assets/js/users.js"></script>
</body>

</html>