<?php
include 'includes/head.php';
include 'includes/auth.php';

requireLogin()

?>

<body>
  <!-- <div id="global-loader">
    <div class="whirly-loader"> </div>
  </div> -->

  <div class="main-wrapper">

    <?php include 'includes/header.php' ?>

    <?php include 'includes/sidebar.php' ?>

    <div class="page-wrapper">
      <div class="content">

        <div class="row">
          <!-- Total Orders Today -->
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget">
              <div class="dash-widgetimg">
                <span><img src="assets/img/icons/dash1.svg" alt="img"></span>
              </div>
              <div class="dash-widgetcontent">
                <h5><span class="counters" data-type="todays_orders">0</span></h5>
                <h6>Today's Orders</h6>
              </div>
            </div>
          </div>

          <!-- Total Sales Today -->
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash1">
              <div class="dash-widgetimg">
                <span><img src="assets/img/icons/dash2.svg" alt="img"></span>
              </div>
              <div class="dash-widgetcontent">
                <h5>GHS <span class="counters" data-type="todays_sales">0.00</span></h5>
                <h6>Today's Sales</h6>
              </div>
            </div>
          </div>

          <!-- Weekly Sales -->
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash2">
              <div class="dash-widgetimg">
                <span><img src="assets/img/icons/dash3.svg" alt="img"></span>
              </div>
              <div class="dash-widgetcontent">
                <h5>GHS <span class="counters" data-type="weekly_sales">0.00</span></h5>
                <h6>Weekly Sales</h6>
              </div>
            </div>
          </div>

          <!-- Monthly Sales -->
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash3">
              <div class="dash-widgetimg">
                <span><img src="assets/img/icons/dash4.svg" alt="img"></span>
              </div>
              <div class="dash-widgetcontent">
                <h5>GHS <span class="counters" data-type="monthly_sales">0.00</span></h5>
                <h6>Monthly Sales</h6>
              </div>
            </div>
          </div>

          <!-- Today's Customers -->
          <div class="col-lg-4 col-sm-6 col-12 d-flex">
            <div class="dash-count">
              <div class="dash-counts">
                <h4 id="todays_customers">0</h4>
                <h5>Today's Customers</h5>
              </div>
              <div class="dash-imgs">
                <i data-feather="user"></i>
              </div>
            </div>
          </div>

          <!-- Total Admins -->
          <div class="col-lg-4 col-sm-6 col-12 d-flex">
            <div class="dash-count das1">
              <div class="dash-counts">
                <h4 id="total_admins">0</h4>
                <h5>Admins</h5>
              </div>
              <div class="dash-imgs">
                <i data-feather="user-check"></i>
              </div>
            </div>
          </div>

          <!-- Total Cashiers -->
          <div class="col-lg-4 col-sm-6 col-12 d-flex">
            <div class="dash-count das2">
              <div class="dash-counts">
                <h4 id="total_cashiers">0</h4>
                <h5>Cashiers</h5>
              </div>
              <div class="dash-imgs">
                <i data-feather="file-text"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Today's Customers Table -->
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Today's Customers</h4>
            <div class="table-responsive">
              <table class="table" id="dashboard-customers">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Customer Name</th>
                    <th>Contact</th>
                    <th>Items</th>
                    <th>Total Amount</th>
                    <th>Created By</th>
                    <th>Time</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Table content will be loaded dynamically -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <?php if ($_SESSION['sysadmin'] || $_SESSION['role'] === 'admin'): ?>
          <div class="card mb-0">
            <div class="card-body">
              <h4 class="card-title">Recently Added Products</h4>
              <div class="table-responsive dataview">
                <table class="table">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Product Name</th>
                      <th>Image</th>
                      <th>Category</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    include 'includes/db_connection.php';
                    include 'includes/sendResponse.php';
                    $query = "SELECT p.*, c.name as category_name 
                             FROM products p
                             LEFT JOIN categories c ON p.category_id = c.id
                             ORDER BY p.name LIMIT 8";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                      $count = 1;
                      while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                          <td><?php echo $count++; ?></td>
                          <td><a href="javascript:void(0);"><?php echo $row['name']; ?></a></td>
                          <td class="productimgname">
                            <a class="product-img" href="/products">
                              <?php if ($row['image'] && !empty($row['image'])): ?>
                                <img src="php/<?php echo $row['image']; ?>" alt="Profile" class="img-fluid">
                              <?php else: ?>
                                <img src="assets/img/boba/boba-c.png" alt="Default Profile" class="img-fluid">
                              <?php endif; ?>
                            </a>
                            <a href="/products"><?php echo $row['name']; ?></a>
                          </td>
                          <td><?php echo $row['category_name']; ?></td>
                        </tr>
                      <?php
                      }
                      ?>
                      <tr>
                        <td colspan="4" class="text-center">
                          <a href="/products"> View All products</a>
                        </td>
                      </tr>
                    <?php
                    } else {
                    ?>
                      <tr>
                        <td colspan="6" class="text-center">No products found</td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <?php include 'includes/scripts.php' ?>
  </body>

</html>