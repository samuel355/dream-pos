<?php include 'includes/head.php';
include 'includes/auth.php';

requireAdmin();
?>

<style>
  .dataTables_wrapper .dt-buttons {
    margin-bottom: 1rem;
  }

  .dt-button {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.2rem;
  }

  .dataTables_processing {
    background: rgba(255, 255, 255, 0.9) !important;
    border: 1px solid #ddd;
    border-radius: 3px;
  }

  .table.dataTable {
    margin-top: 1rem !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #7367f0 !important;
    color: white !important;
    border: 1px solid #7367f0 !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #7367f0 !important;
    color: white !important;
    border: 1px solid #7367f0 !important;
  }
</style>

<body>
  <div id="global-loader">
    <div class="whirly-loader"> </div>
  </div>

  <div class="main-wrapper">

    <?php include 'includes/header.php' ?>

    <?php include 'includes/sidebar.php' ?>

    <div class="page-wrapper">
      <div class="content">
        <div class="page-header">
          <div class="page-title">
            <h4>Orders List</h4>
            <h6>Manage Your Orders</h6>
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
                    <a href="php/export-orders.php?type=pdf" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="pdf">
                      <img src="assets/img/icons/pdf.svg" alt="pdf">
                    </a>
                  </li>
                  <li>
                    <a href="php/export-orders.php" data-bs-toggle="tooltip" data-bs-placement="top" title="excel">
                      <img src="assets/img/icons/excel.svg" alt="excel">
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0);" onclick="window.print()" data-bs-toggle="tooltip" data-bs-placement="top" title="print">
                      <img src="assets/img/icons/printer.svg" alt="print">
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table" id="ordersTable">
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
                    <th>Customer Contact</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="ordersTableBody">
                  <!-- Data will be loaded here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>


  <?php include 'includes/scripts.php'; ?>
  </body>

</html>