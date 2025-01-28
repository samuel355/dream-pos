document.addEventListener("DOMContentLoaded", function () {
  loadDashboardData();
  // Refresh every 30 seconds
  setInterval(loadDashboardData, 30000);
});

function loadDashboardData() {
  fetch("php/get-dashboard-stats.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        updateDashboard(data.data);
      }
    })
    .catch((error) => console.error("Error:", error));
}

function updateDashboard(data) {
  // Update counters
  const today_orders = document.querySelector('[data-type="todays_orders"]');
  const today_sales = document.querySelector('[data-type="todays_sales"]');
  const weekly_sales = document.querySelector('[data-type="weekly_sales"]');
  const monthly_sales = document.querySelector('[data-type="monthly_sales"]');
  const today_customers = document.getElementById("todays_customers");
  const total_admins = document.getElementById("total_admins");
  const total_cashiers = document.getElementById("total_cashiers");

  if (today_orders) {
    today_orders.textContent = data.todays_orders;
  }
  if (today_sales) {
    today_sales.textContent = formatNumber(data.todays_sales);
  }
  if (weekly_sales) {
    weekly_sales.textContent = formatNumber(data.weekly_sales);
  }
  if (monthly_sales) {
    monthly_sales.textContent = formatNumber(data.monthly_sales);
  }
  if (today_customers) {
    today_customers.textContent = data.todays_customers;
  }
  if(total_admins){
    total_admins.textContent = data.total_admins;
  }
  if(total_cashiers){
    total_cashiers.textContent = data.total_cashiers;
  }
  
  // Update customers table
  showCustomersInTable(data.todays_orders_list);
}

function showCustomersInTable(orders) {
  const table = $("#dashboard-customers");

  // Destroy existing DataTable if it exists
  if ($.fn.DataTable.isDataTable(table)) {
    table.DataTable().destroy();
  }

  const tbody = table.find("tbody");
  tbody.empty();

  orders.forEach((order, i) => {
    tbody.append(
      `
        <tr>
          <td>${i + 1}</td>
          <td>${escapeHtml(order.customer_name)}</td>
          <td>${escapeHtml(order.items || "No items")}</td>
          <td>GHS ${formatNumber(order.total_amount)}</td>
          <td>${escapeHtml(order.created_by)}</td>
          <td>${formatTime(order.created_at)}</td>
        </tr>
      `
    );
  });

  //Initialize datatable
  table.DataTable({
    responsive: true,
    ordering: true,
    searching: true,
    paging: true,
    pageLength: 10,
    dom: "Bfrtip",
    buttons: ["copy", "csv", "excel", "pdf", "print"],
    language: {
      emptyTable: "No customers available",
    },
    columnDefs: [
      {
        targets: 0, // Image column
        orderable: false,
      },
      {
        targets: -1, // Action column
        orderable: false,
      },
    ],
  });
}

function formatNumber(number) {
  return new Intl.NumberFormat("en-GH", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(number);
}

function formatTime(dateString) {
  return new Date(dateString).toLocaleTimeString("en-US", {
    hour: "2-digit",
    minute: "2-digit",
    hour12: true,
  });
}

function escapeHtml(str) {
  if (!str) return "";
  const div = document.createElement("div");
  div.textContent = str;
  return div.innerHTML;
}
