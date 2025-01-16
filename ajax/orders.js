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
        //displayOrders(data.orders);
      }
    })
    .catch((error) => console.error("Error:", error));
}

