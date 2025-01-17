$("#login-form").on("submit", function (e) {
  e.preventDefault();

  var email = $("#email").val();
  var password = $("#password").val();

  if (email === "" || password === "") {
    toastr.error("Enter your email and password");
    return;
  }

  $("#btn-login").text("Logging in...");
  $("#btn-login").prop("disabled", true); // Correctly disable the button

  // Create FormData object
  const formData = new FormData();
  formData.append("email", email);
  formData.append("password", password);

  // Send AJAX request
  fetch("php/login_process.php", {
    method: "POST",
    body: formData, // Use 'body' instead of 'data'
  })
    .then((response) => {
      console.log(response)
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        // Redirect based on user role
        toastr.success("Logged in successfully");
        window.location.href = data.redirect;
      } else {
        // Show error message
        toastr.error(data.message);
        $(".btn-login").text("Sign In");
        $(".btn-login").prop("disabled", false); // Re-enable button
      }
    })
    .catch((error) => {
      console.error("Error:", error); // Log the error for debugging
      toastr.error("Sorry, an error occurred while logging in");
      $(".btn-login").text("Sign In");
      $(".btn-login").prop("disabled", false); // Re-enable button
    });
});
