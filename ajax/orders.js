document.addEventListener("DOMContentLoaded", function () {
  // Load initial orders
  loadOrders();

  // Set up periodic refresh (every 3 seconds)
  setInterval(loadOrders, 3000);
});

//Load Orders
function loadOrders() {
  fetch("php/get-orders.php")
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        displayOrders(data.orders);
      }
    })
    .catch((error) => console.error("Error:", error));
}

//Display orders
function displayOrders(orders) {
  const tbody = document.getElementById("ordersTableBody");
  if(tbody === null) return;
  
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
  const options = {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  return new Date(dateString).toLocaleDateString("en-US", options);
}

//Delete order
function deleteOrder(orderId) {
  if (confirm("Are you sure you want to delete this order?")) {
    fetch("php/delete-order.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ order_id: orderId }),
    })
      .then((response) => {
        console.log(response);
        return response.json();
      })
      .then((data) => {
        if (data.status === "success") {
          loadOrders(); // Reload orders after deletion
          toastr.success("Order deleted successfully");
        } else {
          console.log(data);
          toastr.error("Error deleting order");
        }
      })
      .catch((error) => console.error("Error:", error));
  }
}
