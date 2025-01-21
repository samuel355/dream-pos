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

    <?php include_once('includes/header.php') ?>

    <?php include_once('includes/sidebar.php') ?>


    <div class="page-wrapper">
      <div class="content">
        <div class="row">
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget">
              <div class="dash-widgetimg">
                <span><img src="assets/img/icons/dash1.svg" alt="img"></span>
              </div>
              <div class="dash-widgetcontent">
                <h5>$<span class="counters" data-count="307144.00">$307,144.00</span></h5>
                <h6>Total Purchase Due</h6>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash1">
              <div class="dash-widgetimg">
                <span><img src="assets/img/icons/dash2.svg" alt="img"></span>
              </div>
              <div class="dash-widgetcontent">
                <h5>$<span class="counters" data-count="4385.00">$4,385.00</span></h5>
                <h6>Total Sales Due</h6>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash2">
              <div class="dash-widgetimg">
                <span><img src="assets/img/icons/dash3.svg" alt="img"></span>
              </div>
              <div class="dash-widgetcontent">
                <h5>$<span class="counters" data-count="385656.50">385,656.50</span></h5>
                <h6>Total Sale Amount</h6>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash3">
              <div class="dash-widgetimg">
                <span><img src="assets/img/icons/dash4.svg" alt="img"></span>
              </div>
              <div class="dash-widgetcontent">
                <h5>$<span class="counters" data-count="40000.00">400.00</span></h5>
                <h6>Total Sale Amount</h6>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count">
              <div class="dash-counts">
                <h4>100</h4>
                <h5>Customers</h5>
              </div>
              <div class="dash-imgs">
                <i data-feather="user"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das1">
              <div class="dash-counts">
                <h4>100</h4>
                <h5>Suppliers</h5>
              </div>
              <div class="dash-imgs">
                <i data-feather="user-check"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das2">
              <div class="dash-counts">
                <h4>100</h4>
                <h5>Purchase Invoice</h5>
              </div>
              <div class="dash-imgs">
                <i data-feather="file-text"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das3">
              <div class="dash-counts">
                <h4>105</h4>
                <h5>Sales Invoice</h5>
              </div>
              <div class="dash-imgs">
                <i data-feather="file"></i>
              </div>
            </div>
          </div>
        </div>

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
                  include_once('includes/db_connection.php');
                  include_once('includes/sendResponse.php');
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
      </div>
    </div>
  </div>

  <?php include_once('includes/scripts.php') ?>