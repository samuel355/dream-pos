let ordersTable;

document.addEventListener("DOMContentLoaded", function () {
  initializeDataTable();
  loadOrders();

  if (typeof feather !== "undefined") {
    feather.replace();
  }

  setTimeout(() => {
    document.querySelectorAll(".dt-button").forEach((button) => {
      button.classList.add("btn", "btn-primary", "btn-sm");
    });
  }, 100);
});

function initializeDataTable() {
  ordersTable = $("#ordersTable").DataTable({
    processing: true,
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      {
        extend: "collection",
        text: "Export",
        buttons: ["copy", "excel", "csv", "pdf", "print"],
      },
    ],
    columns: [
      {
        data: null,
        orderable: false,
        render: function (data) {
          return `
                      <label class="checkboxs">
                          <input type="checkbox" class="order-checkbox" value="${data.id}">
                          <span class="checkmarks"></span>
                      </label>
                  `;
        },
      },
      { data: "receipt_number" },
      { data: "customer_name" },
      { data: "customer_phone" },
      {
        data: "total_amount",
        render: function (data) {
          return "GHS " + formatNumber(data);
        },
      },
      {
        data: "created_at",
        render: function (data) {
          return formatDate(data);
        },
      },
      {
        data: null,
        orderable: false,
        render: function (data) {
          return `
                      <div class="btn-group">
                          <button class="btn btn-sm btn-info" onclick="viewOrderDetails(${data.id})">
                              <i data-feather="eye"></i>
                          </button>
                          <button class="btn btn-sm btn-success" onclick="printReceipt(${data.id})">
                              <i data-feather="printer"></i>
                          </button>
                          <button class="btn btn-sm btn-danger" onclick="deleteOrder(${data.id})">
                              <i data-feather="trash-2"></i>
                          </button>
                      </div>
                  `;
        },
      },
    ],
    drawCallback: function () {
      // Reinitialize feather icons
      if (typeof feather !== "undefined") {
        feather.replace();
      }
    },
  });
}

function loadOrders() {
  fetch("php/get-orders.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        // Clear and reload DataTable
        ordersTable.clear().rows.add(data.orders).draw();
      } else {
        toastr.error(data.message || "Error loading orders");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("Error loading orders");
    });
}

function displayOrders(orders) {
  const tbody = document.getElementById("ordersTableBody");

  if (!orders || orders.length === 0) {
    tbody.innerHTML = `
          <tr>
              <td colspan="8" class="text-center">No orders found</td>
          </tr>
      `;
    return;
  }

  tbody.innerHTML = orders
    .map(
      (order) => `
      <tr>
          <td>
              <label class="checkboxs">
                  <input type="checkbox" class="order-checkbox" value="${
                    order.id
                  }">
                  <span class="checkmarks"></span>
              </label>
          </td>
          <td>${order.receipt_number}</td>
          <td>${escapeHtml(order.customer_name)}</td>
          <td>${escapeHtml(order.customer_phone)}</td>
          <td>GHS ${formatNumber(order.total_amount)}</td>
          <td>
              <button class="btn btn-sm btn-info" onclick="viewOrderDetails(${
                order.id
              })">
                  View Items
              </button>
          </td>
          <td>${formatDate(order.created_at)}</td>
          <td>
              <div class="btn-group">
                  <button class="btn btn-sm btn-success" onclick="printReceipt(${
                    order.id
                  })">
                      <i data-feather="printer"></i>
                  </button>
                  <button class="btn btn-sm btn-danger" onclick="deleteOrder(${
                    order.id
                  })">
                      <i data-feather="trash-2"></i>
                  </button>
              </div>
          </td>
      </tr>
  `
    )
    .join("");

  // Reinitialize feather icons
  if (typeof feather !== "undefined") {
    feather.replace();
  }
}

function viewOrderDetails(orderId) {
  fetch(`php/get-order-details.php?id=${orderId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        showOrderDetailsModal(data.data);
      } else {
        toastr.error(data.message || "Error loading order details");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("Error loading order details");
    });
}

function showOrderDetailsModal(order) {
  const modalHtml = `
      <div class="modal fade" id="orderModal">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Order Details</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row mb-3">
                          <div class="col-md-6">
                              <p><strong>Invoice:</strong> ${
                                order.receipt_number
                              }</p>
                              <p><strong>Customer:</strong> ${
                                order.customer_name
                              }</p>
                              <p><strong>Contact:</strong> ${
                                order.customer_phone
                              }</p>
                          </div>
                          <div class="col-md-6 text-end">
                              <p><strong>Date:</strong> ${formatDate(
                                order.created_at
                              )}</p>
                              <p><strong>Total Amount:</strong> GHS ${formatNumber(
                                order.total_amount
                              )}</p>
                          </div>
                      </div>
                      <div class="table-responsive">
                          <table class="table">
                              <thead>
                                  <tr>
                                      <th>Item</th>
                                      <th>Quantity</th>
                                      <th>Unit Price</th>
                                      <th>Total</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  ${order.items
                                    .map(
                                      (item) => `
                                      <tr>
                                          <td>${item.product_name}</td>
                                          <td>${item.quantity}</td>
                                          <td>GHS ${formatNumber(
                                            item.price
                                          )}</td>
                                          <td>GHS ${formatNumber(
                                            item.quantity * item.price
                                          )}</td>
                                      </tr>
                                  `
                                    )
                                    .join("")}
                              </tbody>
                              <tfoot>
                                  <tr>
                                      <th colspan="3" class="text-end">Total:</th>
                                      <th>GHS ${formatNumber(
                                        order.total_amount
                                      )}</th>
                                  </tr>
                              </tfoot>
                          </table>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" onclick="printReceipt(${
                        order.id
                      })">
                          Print Receipt
                      </button>
                  </div>
              </div>
          </div>
      </div>
  `;

  // Remove existing modal if any
  const existingModal = document.getElementById("orderModal");
  if (existingModal) {
    existingModal.remove();
  }

  // Add new modal
  document.body.insertAdjacentHTML("beforeend", modalHtml);

  // Show modal
  const modal = new bootstrap.Modal(document.getElementById("orderModal"));
  modal.show();
}

function printReceipt(orderId) {
  fetch(`php/get-order-details.php?id=${orderId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        const printWindow = window.open("", "_blank", "width=600,height=800");
        generateReceiptHTML(data.data, printWindow);
      } else {
        toastr.error(data.message || "Error loading receipt");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("Error loading receipt");
    });
}

function deleteOrder(orderId) {
  if (!confirm("Are you sure you want to delete this order?")) {
    return;
  }

  fetch("php/delete-order.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ order_id: orderId }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        toastr.success(data.message);
        loadOrders(); // This will refresh the DataTable
      } else {
        toastr.error(data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("Error deleting order");
    });
}

function initializeSelectAll() {
  const selectAllCheckbox = document.getElementById("select-all");
  if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener("change", function () {
      const checkboxes = document.getElementsByClassName("order-checkbox");
      for (let checkbox of checkboxes) {
        checkbox.checked = this.checked;
      }
    });
  }
}

function formatNumber(number) {
  return parseFloat(number).toFixed(2);
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
}

function escapeHtml(str) {
  const div = document.createElement("div");
  div.textContent = str;
  return div.innerHTML;
}

function generateReceiptHTML(order, printWindow) {
  const html = `
      <!DOCTYPE html>
      <html>
      <head>
          <title>Receipt #${order.receipt_number}</title>
          <style>
              body {
                  font-family: 'Courier New', monospace;
                  padding: 20px;
                  max-width: 300px;
                  margin: 0 auto;
              }
              .header, .footer {
                  text-align: center;
                  margin: 20px 0;
              }
              .items {
                  margin: 20px 0;
              }
              .total {
                  text-align: right;
                  margin-top: 20px;
                  border-top: 1px dashed #000;
                  padding-top: 10px;
              }
              @media print {
                  .no-print {
                      display: none;
                  }
              }
          </style>
      </head>
      <body>
          <div class="header">
              <h2>Your Store Name</h2>
              <p>Receipt #${order.receipt_number}</p>
              <p>Date: ${formatDate(order.created_at)}</p>
              <p>Customer: ${order.customer_name}</p>
              <p>Contact: ${order.customer_phone}</p>
          </div>
          
          <div class="items">
              ${order.items
                .map(
                  (item) => `
                  <div>
                      ${item.product_name}<br>
                      ${item.quantity} x GHS ${formatNumber(
                    item.price
                  )} = GHS ${formatNumber(item.quantity * item.price)}
                  </div>
                  <br>
              `
                )
                .join("")}
          </div>
          
          <div class="total">
              <h3>Total: GHS ${formatNumber(order.total_amount)}</h3>
          </div>
          
          <div class="footer">
              <p>Thank you for your purchase!</p>
              <p>Please come again</p>
          </div>

          <div class="no-print" style="text-align: center; margin-top: 20px;">
              <button onclick="window.print();" style="padding: 10px 20px;">
                  Print Receipt
              </button>
          </div>
      </body>
      </html>
  `;

  printWindow.document.write(html);
  printWindow.document.close();
}
