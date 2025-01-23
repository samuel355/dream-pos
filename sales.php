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
    <?php include_once('includes/header.php') ?>
    <?php include_once('includes/sidebar.php') ?>

    <div class="page-wrapper">
      <div class="content">
        <!-- Page Header -->
        <div class="page-header">
          <div class="row">
            <div class="col-12">
              <h3 class="page-title">Sales Report</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button id="data-export-report" type="button" class="btn btn-primary">
                <i data-feather="download"></i> Export as Excel
              </button>
            </div>
          </div>
        </div>

        <!-- Sales Filter -->
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Date Range</label>
                  <select class="form-control" id="dateRange">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="custom">Custom Range</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3 custom-dates" style="display: none;">
                <div class="form-group">
                  <label>Start Date</label>
                  <input type="date" class="form-control" id="startDate">
                </div>
              </div>
              <div class="col-md-3 custom-dates" style="display: none;">
                <div class="form-group">
                  <label>End Date</label>
                  <input type="date" class="form-control" id="endDate">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>&nbsp;</label>
                  <button class="btn btn-primary w-100" onclick="loadSales()">
                    Filter
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sales Summary Cards -->
        <div class="row">
          <div class="col-sm-6 col-xl-3">
            <div class="card">
              <div class="card-body">
                <div class="dash-widget-header">
                  <div>
                    <h3 class="sales-total">0</h3>
                    <h6>Total Sales</h6>
                  </div>
                  <div class="dash-widget-icon">
                    <!-- <i data-feather="dollar-sign"></i> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card">
              <div class="card-body">
                <div class="dash-widget-header">
                  <div>
                    <h3 class="orders-count">0</h3>
                    <h6>Total Orders</h6>
                  </div>
                  <div class="dash-widget-icon">
                    <!-- <i data-feather="shopping-cart"></i> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card">
              <div class="card-body">
                <div class="dash-widget-header">
                  <div>
                    <h3 class="customers-count">0</h3>
                    <h6>Total Customers</h6>
                  </div>
                  <div class="dash-widget-icon">
                    <!-- <i data-feather="users"></i> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card">
              <div class="card-body">
                <div class="dash-widget-header">
                  <div>
                    <h3 class="avg-sale">0</h3>
                    <h6>Average Sale</h6>
                  </div>
                  <div class="dash-widget-icon">
                    <!-- <i data-feather="bar-chart-2"></i> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sales Table -->
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped" id="salesTable">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Created By</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Sales data will be loaded here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include_once('includes/scripts.php') ?>
  <script src="assets/js/sales.js"></script>
</body>

</html>