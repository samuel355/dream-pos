document.addEventListener("DOMContentLoaded", function () {
  // Initialize feather icons if you're using them
  if (typeof feather !== "undefined") {
    feather.replace();
  }
});

function logout() {
  if (confirm("Are you sure you want to logout?")) {
    fetch("php/logout.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          // Show success message
          toastr.success(data.message);

          // Redirect after a short delay
          setTimeout(() => {
            window.location.href = data.redirect;
          }, 1000);
        } else {
          toastr.error(data.message || "Error logging out");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        toastr.error("Error logging out");
      });
  }
}

// Function to update profile image
function updateProfileImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      // Update all profile images on the page
      document
        .querySelectorAll(".user-img img, .user-letter img")
        .forEach((img) => {
          img.src = e.target.result;
        });
    };

    reader.readAsDataURL(input.files[0]);
  }
}
