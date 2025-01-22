document.addEventListener("DOMContentLoaded", function () {
  initializeCustomersDataTable();
  loadCustomers();

  // Add customer form submission
  document
    .getElementById("add-customer-form")
    .addEventListener("submit", addCustomer);

  if (typeof feather !== "undefined") {
    feather.replace();
  }
});

function initializeCustomersDataTable() {
  customersTable = $("#customersTable").DataTable({
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
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },
      { data: "name" },
      { data: "contact" },
      { data: "items" },
      { data: "total" },
      { data: "created_at" },
      {
        data: null,
        render: function (data) {
          return `
                      <div class="btn-group">
                          <button class="btn btn-sm btn-danger" onclick="deleteCustomer(${data.id})">
                              <i data-feather="trash-2"></i>
                          </button>
                      </div>
                  `;
        },
      },
    ],
    drawCallback: function () {
      feather.replace();
    },
  });
}

function loadCustomers() {
  fetch("php/get-customers.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        customersTable.clear().rows.add(data.customers).draw();
      } else {
        toastr.error(data.message || "Error loading customers");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("Error loading customers");
    });
}


function deleteCustomer(customerId) {
  if (confirm("Are you sure you want to delete this customer?")) {
    fetch("php/delete-customer.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ customer_id: customerId }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          toastr.success(data.message);
          loadCustomers();
        } else {
          toastr.error(data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        toastr.error("Error deleting customer");
      });
  }
}
