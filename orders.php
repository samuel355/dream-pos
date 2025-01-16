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
            <h4>Product List</h4>
            <h6>Manage your products</h6>
          </div>
          <div class="page-btn">
            <a href="addproduct.html" class="btn btn-added"><img src="assets/img/icons/plus.svg" alt="img"
                class="me-1">Add New Product</a>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="table-top">
              <div class="search-set">
                <div class="search-path">
                  <a class="btn btn-filter" id="filter_search">
                    <img src="assets/img/icons/filter.svg" alt="img">
                    <span><img src="assets/img/icons/closes.svg" alt="img"></span>
                  </a>
                </div>
                <div class="search-input">
                  <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg"
                      alt="img"></a>
                </div>
              </div>
              <div class="wordset">
                <ul>
                  <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                        src="assets/img/icons/pdf.svg" alt="img"></a>
                  </li>
                  <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                        src="assets/img/icons/excel.svg" alt="img"></a>
                  </li>
                  <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                        src="assets/img/icons/printer.svg" alt="img"></a>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Search -->
            <div class="card mb-0" id="filter_inputs">
              <div class="card-body pb-0">
                <div class="row">
                  <div class="col-lg-12 col-sm-12">
                    <div class="row">
                      <div class="col-lg col-sm-6 col-12">
                        <div class="form-group">
                          <select class="select">
                            <option>Choose Product</option>
                            <option>Macbook pro</option>
                            <option>Orange</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg col-sm-6 col-12">
                        <div class="form-group">
                          <select class="select">
                            <option>Choose Category</option>
                            <option>Computers</option>
                            <option>Fruits</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg col-sm-6 col-12">
                        <div class="form-group">
                          <select class="select">
                            <option>Choose Sub Category</option>
                            <option>Computer</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg col-sm-6 col-12">
                        <div class="form-group">
                          <select class="select">
                            <option>Brand</option>
                            <option>N/D</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg col-sm-6 col-12 ">
                        <div class="form-group">
                          <select class="select">
                            <option>Price</option>
                            <option>150.00</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-1 col-sm-6 col-12">
                        <div class="form-group">
                          <a class="btn btn-filters ms-auto"><img
                              src="assets/img/icons/search-whites.svg" alt="img"></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table  datanew">
                <thead>
                  <tr>
                    <th>
                      <label class="checkboxs">
                        <input type="checkbox" id="select-all">
                        <span class="checkmarks"></span>
                      </label>
                    </th>
                    <th>Invoice No</th>
                    <th>Customer Name</th>
                    <th>Customer Contact </th>
                    <th>Amount</th>
                    <th>Details</th>
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="ordersTableBody">
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>


  <?php include_once('includes/scripts.php') ?>