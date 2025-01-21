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
  document.querySelector('[data-type="todays_orders"]').textContent =
    data.todays_orders;
  document.querySelector('[data-type="todays_sales"]').textContent =
    formatNumber(data.todays_sales);
  document.querySelector('[data-type="weekly_sales"]').textContent =
    formatNumber(data.weekly_sales);
  document.querySelector('[data-type="monthly_sales"]').textContent =
    formatNumber(data.monthly_sales);

  // Update user counts
  document.getElementById("todays_customers").textContent =
    data.todays_customers;
  document.getElementById("total_admins").textContent = data.total_admins;
  document.getElementById("total_cashiers").textContent = data.total_cashiers;

  // Update customers table
  updateCustomersTable(data.todays_orders_list);
}

function updateCustomersTable(orders) {
  const tbody = document.getElementById("todaysCustomersTable");
  if (!orders || orders.length === 0) {
    tbody.innerHTML =
      '<tr><td colspan="6" class="text-center">No orders today</td></tr>';
    return;
  }

  let html = "";
  orders.forEach((order, index) => {
    html += `
          <tr>
              <td>${index + 1}</td>
              <td>${escapeHtml(order.customer_name)}</td>
              <td>${escapeHtml(order.customer_phone)}</td>
              <td>${escapeHtml(order.items || "No items")}</td>
              <td>GHS ${formatNumber(order.total_amount)}</td>
              <td>${formatTime(order.created_at)}</td>
          </tr>
      `;
  });

  tbody.innerHTML = html;
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
