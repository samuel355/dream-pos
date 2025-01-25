document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("forgot-password-form");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const email = document.getElementById("email").value;

      if (!email) {
        toastr.error("Please enter your email");
        return;
      }

      const formData = new FormData();
      formData.append("email", email);

      fetch("php/check-email.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            toastr.success(data.message);
            setTimeout(() => {
              window.location.href = "reset-password.php";
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
