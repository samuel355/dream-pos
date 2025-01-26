document.addEventListener("DOMContentLoaded", function () {
  // Load initial notifications
  loadNotifications();

  // Set up periodic refresh (every 3 seconds)
  setInterval(loadNotifications, 3000);

  // Setup clear all notifications
  const clear_notification = document.querySelector(".clear-noti")
  if(clear_notification){
    clear_notification.addEventListener("click", clearAllNotifications);
  } 
  
  const clear_all_notification = document.querySelector(".clear-all-noti")
  if(clear_all_notification){
    clear_all_notification.addEventListener("click", clearAllNotifications)
  }
  
});

function loadNotifications() {
  fetch("php/get-notifications.php")
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        displayNotifications(data.notifications);
      }
    })
    .catch((error) => console.error("Error:", error));
}

//Display notifications
function displayNotifications(notifications) {
  const notificationList = document.querySelector(".notifications-content");
  const notificationBadge = document.querySelector(".nav-item-box .badge");

  if (notificationList === null || notificationBadge === null) return;

  // Update badge count
  notificationBadge.textContent = notifications.length;

  // Show/hide badge
  if (notifications.length === 0) {
    notificationBadge.style.display = "none";
  } else {
    notificationBadge.style.display = "block";
  }

  // Update notification list
  let html = "";
  notifications.forEach((notification) => {
    html += `
          <li class="notification-message">
              <a href="order-details.php?id=${notification.order_id}" 
                 onclick="markNotificationRead(${notification.id}, event)">
                  <div class="media d-flex">
                      <div class="media-body d-flex flex-grow-1" style="justify-content: space-between; font-weight: bold">
                          <div class="noti-details">
                              New order from <span class="noti-title">${
                                notification.customer_name
                              }</span>
                              <br>Amount: <span class="noti-title">GHS ${parseFloat(
                                notification.total_amount
                              ).toFixed(2)}</span>
                              <p>ITEMS: <span style="margin-left: 5px">${
                                notification.items
                              }</span>  </p>
                          </div>
                          <p class="noti-time">
                              <span class="notification-time">${formatTimeAgo(
                                notification.created_at
                              )}</span>
                          </p>
                      </div>
                  </div>
              </a>
          </li>
      `;
  });

  if (notifications.length === 0) {
    html =
      '<li class="notification-message"><div class="media d-flex"><div class="media-body flex-grow-1"><p class="text-center">No new notifications</p></div></div></li>';
  }

  notificationList.innerHTML = html;
}

//Format time
function formatTimeAgo(dateString) {
  const date = new Date(dateString);
  const now = new Date();
  const diffTime = Math.abs(now - date);
  const diffMinutes = Math.floor(diffTime / (1000 * 60));
  const diffHours = Math.floor(diffTime / (1000 * 60 * 60));
  const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

  if (diffMinutes < 60) {
    return `${diffMinutes} mins ago`;
  } else if (diffHours < 24) {
    return `${diffHours} hours ago`;
  } else {
    return `${diffDays} days ago`;
  }
}

//Clear all notifications
function clearAllNotifications() {
  if (!confirm("Are you sure you want to clear all notifications?")) return;

  fetch("php/clear-notifications.php", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        loadNotifications();
        toastr.success("All notifications cleared");
      }
      if(data.status === 'error'){
        toastr.error(data.message)
      }
    })
    .catch((error) => console.error("Error:", error));
}
