document.addEventListener("DOMContentLoaded", function () {
  
  const form = document.getElementById("add-user-form");
  if(form === null) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Create FormData object
    const formData = new FormData(this);
    const fullname  = $('#fullname').val()
    const email = $('#email').val();
    const password = $('#password').val();
    const repassword = $('#repassword').val();
    const phone = $('#phone').val()
    const role = $('#role').val();

    if(fullname === '' || email === '' || phone === '' || password === '' || repassword === '' ){
      toastr.error('Please fill all fields correctly')
      return
    }
    if(role === 'Select'){
      toastr.error('Select role for this user')
      return
    }
    if(password !== repassword){
      toastr.error('Your passwords do not match')
      return;
    }
    if(password.length < 6){
      toastr.error('Your password is weak')
      return
    }

    // Send AJAX request
    fetch("php/add_user_process.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        console.log(response)
        return response.json()
      })
      .then((data) => {
        if (data.status === "success") {
          toastr.success(data.message)
          form.reset();
          document.getElementById("preview").style.display = "none";
        } else {
          toastr.error(data.message)
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        toastr.error("An error occurred. Please try again.")
      });
  });
});

