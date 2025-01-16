document.addEventListener("DOMContentLoaded", function () {
  // Load initial notifications
  loadNotifications();

  // Set up periodic refresh (every 3 seconds)
  setInterval(loadNotifications, 3000);

  // Setup clear all notifications
  // document
  //   .querySelector(".clear-noti")
  //   .addEventListener("click", clearAllNotifications);
});

function loadNotifications() {
  fetch("php/get-notifications.php")
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        console.log('notifications: ', data.notifications)
        //updateNotificationUI(data.notifications);
      }
    })
    .catch((error) => console.error("Error:", error));
}
