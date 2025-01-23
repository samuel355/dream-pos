document.addEventListener("DOMContentLoaded", function () {
  initializeDateRangeFilter();
  loadSales();

  const exportButton = document.getElementById("data-export-report");

  if (exportButton) {
    exportButton.addEventListener("click", exportSalesReport);
  }

  if (typeof feather !== "undefined") {
    feather.replace();
  }
});

function initializeDateRangeFilter() {
  const dateRange = document.getElementById("dateRange");
  const customDates = document.querySelectorAll(".custom-dates");

  dateRange.addEventListener("change", function () {
    const showCustomDates = this.value === "custom";
    customDates.forEach((elem) => {
      elem.style.display = showCustomDates ? "block" : "none";
    });
  });
}

function loadSales() {
  const dateRange = document.getElementById("dateRange").value;
  const startDate = document.getElementById("startDate")?.value || "";
  const endDate = document.getElementById("endDate")?.value || "";

  // Show loading state
  document.querySelector("#salesTable tbody").innerHTML = `
      <tr>
          <td colspan="6" class="text-center">Loading...</td>
      </tr>
  `;

  fetch("php/get-sales.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      dateRange: dateRange,
      startDate: startDate,
      endDate: endDate,
    }),
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        updateSalesDisplay(data.data);
      } else {
        toastr.error(data.message || "Error loading sales data");
        // Show error in table
        document.querySelector("#salesTable tbody").innerHTML = `
              <tr>
                  <td colspan="6" class="text-center text-danger">
                      ${data.message || "Error loading sales data"}
                  </td>
              </tr>
          `;
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("Error loading sales data");
      // Show error in table
      document.querySelector("#salesTable tbody").innerHTML = `
          <tr>
              <td colspan="6" class="text-center text-danger">
                  Error loading sales data. Please try again.
              </td>
          </tr>
      `;
    });
}

function updateSalesDisplay(data) {
  // Update summary cards
  document.querySelector(".sales-total").textContent = formatCurrency(
    data.total_sales || 0
  );
  document.querySelector(".orders-count").textContent = data.total_orders || 0;
  document.querySelector(".customers-count").textContent =
    data.total_customers || 0;
  document.querySelector(".avg-sale").textContent = formatCurrency(
    data.average_sale || 0
  );

  // Update sales table
  const tbody = document.querySelector("#salesTable tbody");

  if (!data.sales || data.sales.length === 0) {
    tbody.innerHTML = `
          <tr>
              <td colspan="6" class="text-center">No sales found for the selected period</td>
          </tr>
      `;
    return;
  }

  tbody.innerHTML = data.sales
    .map(
      (sale) => `
      <tr>
          <td>#${sale.id}</td>
          <td>${formatDate(sale.created_at)}</td>
          <td>${escapeHtml(sale.customer_name)}</td>
          <td>${escapeHtml(sale.items || "No items")}</td>
          <td>${formatCurrency(sale.total_amount)}</td>
          <td>${escapeHtml(sale.created_by || 'Cashier 1')}</td>
          <td>
              <button class="btn btn-sm btn-primary" onclick="viewSaleDetails(${
                sale.id
              })">
                  <i data-feather="eye"></i>
              </button>
              <button class="btn btn-sm btn-secondary" onclick="printReceipt(${
                sale.id
              })">
                  <i data-feather="printer"></i>
              </button>
          </td>
      </tr>
  `
    )
    .join("");

  // Reinitialize Feather icons
  if (typeof feather !== "undefined") {
    feather.replace();
  }
}

function viewSaleDetails(saleId) {
  fetch(`php/get-order-details.php?id=${saleId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        showOrderDetailsModal(data);
        console.log(data);
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
  if (!order || !order.id) {
    toastr.error("Invalid order data");
    return;
  }

  const modalHtml = `
      <div class="modal fade" id="orderModal">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Order #${order.id}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                      <div class="order-info mb-3">
                          <p><strong>Customer:</strong> ${
                            order.customer_name || "N/A"
                          }</p>
                          <p><strong>Phone:</strong> ${
                            order.customer_phone || "N/A"
                          }</p>
                          <p><strong>Date:</strong> ${new Date(
                            order.created_at
                          ).toLocaleString()}</p>
                      </div>
                      <div class="order-items">
                          <h6>Order Items</h6>
                          <table class="table">
                              <thead>
                                  <tr>
                                      <th>Item</th>
                                      <th>Quantity</th>
                                      <th>Price</th>
                                      <th>Total</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  ${
                                    order.items
                                      ? order.items
                                          .map(
                                            (item) => `
                                      <tr>
                                          <td>${item.product_name}</td>
                                          <td>${item.quantity}</td>
                                          <td>GHS ${item.total_price}</td>
                                          <td>GHS ${(
                                            item.quantity * item.total_price
                                          ).toFixed(2)}</td>
                                      </tr>
                                  `
                                          )
                                          .join("")
                                      : '<tr><td colspan="4">No items found</td></tr>'
                                  }
                              </tbody>
                              <tfoot>
                                  <tr>
                                      <th colspan="3">Total</th>
                                      <th>GHS ${
                                        order.total_amount || "0.00"
                                      }</th>
                                  </tr>
                              </tfoot>
                          </table>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-12" style="margin-left: 12px; font-weight: 700">
                      <span>Created By : </span>
                      <span>${order.created_by !== '' ? order.created_by : 'Cashier 1'}</span>
                    <div>
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

function printReceipt(saleId) {
  fetch(`php/get-order-details.php?id=${saleId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success" && data) {
        const printWindow = window.open("", "_blank", "width=600,height=800");
        const order = data;

        const receiptHtml = `
                  <!DOCTYPE html>
                  <html>
                  <head>
                      <title>Receipt #${order.id}</title>
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
                          <h2>POPSY BUBBLE TEA SHOP</h2>
                          <p>Receipt #${order.id}</p>
                          <p>Date: ${new Date(
                            order.created_at
                          ).toLocaleString()}</p>
                          <p>Customer: ${order.customer_name || "N/A"}</p>
                          <p>Phone: ${order.customer_phone || "N/A"}</p>
                          <p>Created By: ${order.created_by || "Cashier 1"}</p>
                      </div>
                      
                      <div class="items">
                        
                          ${
                            order.items
                              ? order.items
                                  .map(
                                    (item) => `
                              <div>
                                  ${item.product_name}<br>
                                  ${item.quantity} x GHS ${
                                      item.total_price
                                    } = GHS ${(
                                      item.quantity * item.total_price
                                    ).toFixed(2)}
                              </div>
                              <br>
                          `
                                  )
                                  .join("")
                              : "No items found"
                          }
                      </div>
                      
                      <div class="total">
                          <h3>Total: GHS ${order.total_amount || "0.00"}</h3>
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

        printWindow.document.write(receiptHtml);
        printWindow.document.close();
      } else {
        toastr.error(data.message || "Error loading receipt");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("Error loading receipt");
    });
}

function formatCurrency(amount) {
  return "GHS " + parseFloat(amount || 0).toFixed(2);
}

function formatDate(dateString) {
  if (!dateString) return "";
  return new Date(dateString).toLocaleString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
}

function escapeHtml(str) {
  if (!str) return "";
  const div = document.createElement("div");
  div.textContent = str;
  return div.innerHTML;
}

function exportSalesReport() {
  console.log("clicked in");
  const dateRange = document.getElementById("dateRange").value;
  const startDate = document.getElementById("startDate")?.value || "";
  const endDate = document.getElementById("endDate")?.value || "";

  // Show loading toast
  toastr.info("Preparing export...");

  // Build the URL with parameters
  const params = new URLSearchParams({
    dateRange: dateRange,
    startDate: startDate,
    endDate: endDate,
  });

  // Create a hidden form and submit it (better for handling POST requests)
  const form = document.createElement("form");
  form.method = "POST";
  form.action = "php/export-sales.php";

  // Add the parameters as hidden inputs
  Object.entries({
    dateRange,
    startDate,
    endDate,
  }).forEach(([key, value]) => {
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = key;
    input.value = value;
    form.appendChild(input);
  });

  document.body.appendChild(form);
  form.submit();
  document.body.removeChild(form);
}

function generateReceiptHTML(order, receiptWindow) {
  const html = `
      <!DOCTYPE html>
      <html>
      <head>
          <title>Receipt #${order.id}</title>
          <style>
              body {
                  font-family: monospace;
                  padding: 20px;
                  max-width: 400px;
                  margin: 0 auto;
              }
              .header {
                  text-align: center;
                  margin-bottom: 20px;
              }
              .items {
                  margin: 20px 0;
              }
              .item {
                  margin: 5px 0;
              }
              .totals {
                  margin-top: 20px;
                  text-align: right;
              }
              .footer {
                  text-align: center;
                  margin-top: 20px;
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
              <h2>POPSY BUBBLE TEA SHOP</h2>
              <p>Receipt #${order.id}</p>
              <p>Date: ${formatDate(order.created_at)}</p>
              <p>Customer: ${order.customer_name}</p>
              <p>Contact: ${order.customer_phone}</p>
          </div>
          
          <div class="items">
              ${order.items
                .map(
                  (item) => `
                  <div class="item">
                      <div>${item.product_name}</div>
                      <div>${item.quantity} x ${formatCurrency(
                    item.unit_price
                  )} = ${formatCurrency(item.quantity * item.unit_price)}</div>
                  </div>
              `
                )
                .join("")}
          </div>
          
          <div class="totals">
              <p><strong>Total: ${formatCurrency(
                order.total_amount
              )}</strong></p>
          </div>
          
          <div class="footer">
              <p>Thank you for your purchase!</p>
              <p>Please come again</p>
          </div>

          <div class="no-print" style="text-align: center; margin-top: 20px;">
              <button onclick="window.print()">Print Receipt</button>
          </div>
      </body>
      </html>
  `;

  receiptWindow.document.write(html);
  receiptWindow.document.close();
}
