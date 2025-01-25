document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("reset-password-form");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirm_password").value;

      if (!password || !confirmPassword) {
        toastr.error("Please fill in all fields");
        return;
      }

      if (password !== confirmPassword) {
        toastr.error("Passwords do not match");
        return;
      }

      const formData = new FormData();
      formData.append("password", password);

      fetch("php/reset-password-process.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            toastr.success(data.message);
            setTimeout(() => {
              window.location.href = "login.php";
            }, 1500);
          } else {
            toastr.error(data.message);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          toastr.error("An error occurred. Please try again.");
        });
    });
  }
});
