document.addEventListener("DOMContentLoaded", function () {
  // Initialize SSE connection
  //initializeSSE();

  // Load initial orders
  loadOrders();
});

function loadOrders() {
  fetch("php/get-orders.php")
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        console.log(data.orders)
        displayOrders(data.orders);
      }
    })
    .catch((error) => console.error("Error:", error));
}

//Format orders
function displayOrders(orders) {
  const tbody = document.getElementById("ordersTableBody");
  let html = "";

  orders.forEach((order) => {
    html += `
          <tr>
              <td>
                  <label class="checkboxs">
                      <input type="checkbox">
                      <span class="checkmarks"></span>
                  </label>
              </td>
              <td>${order.receipt_number}</td>
              <td>${order.customer_name}</td>
              <td>${order.customer_phone}</td>
              <td>GHS ${parseFloat(order.total_amount).toFixed(2)}</td>
              <td>${order.items}</td>
              <td>${formatDate(order.created_at)}</td>
              
              <td>
                  <a class="me-3" href="order-details.php?id=${order.id}">
                      <img src="assets/img/icons/eye.svg" alt="img">
                  </a>
                  <a class="confirm-text" href="javascript:void(0);" onclick="deleteOrder(${
                    order.id
                  })">
                      <img src="assets/img/icons/delete.svg" alt="img">
                  </a>
              </td>
          </tr>
      `;
  });

  tbody.innerHTML = html;
}

//Format date
function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString() + ' at ' + date.toLocaleTimeString();
}