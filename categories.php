<?php include_once('includes/head.php') ?>

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
              data-bs-target="#create"><i class="fa fa-plus me-2"></i>Add Customer</a>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="card" id="filter_inputs">
              <div class="card-body pb-0">
                <div class="row">
                  <div class="col-lg-2 col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select">
                        <option>Choose Category</option>
                        <option>Computers</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-2 col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select">
                        <option>Choose Sub Category</option>
                        <option>Fruits</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-2 col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select">
                        <option>Choose Sub Brand</option>
                        <option>Iphone</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-1 col-sm-6 col-12 ms-auto">
                    <div class="form-group">
                      <a class="btn btn-filters ms-auto"><img
                          src="assets/img/icons/search-whites.svg" alt="img"></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

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

  <div class="modal fade" id="create" tabindex="-1" aria-labelledby="create" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Customer Name</label>
                  <input type="text">
                </div>
              </div>
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Email</label>
                  <input type="text">
                </div>
              </div>
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Phone</label>
                  <input type="text">
                </div>
              </div>
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Country</label>
                  <input type="text">
                </div>
              </div>
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>City</label>
                  <input type="text">
                </div>
              </div>
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Address</label>
                  <input type="text">
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <a class="btn btn-submit me-2">Submit</a>
              <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php include_once('includes/scripts.php') ?>