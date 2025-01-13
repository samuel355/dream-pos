document.addEventListener("DOMContentLoaded", function () {
  
  const form = document.getElementById("add-category-form");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Create FormData object
    const formData = new FormData(this);
    const categoryname  = $('#category-name').val()

    if(categoryname === '' ){
      toastr.error('Please Enter category name')
      return
    }

    // Send AJAX request
    fetch("php/add-category-process.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
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
        toastr.error("An error occurred. Please try again..")
      });
  });
});

