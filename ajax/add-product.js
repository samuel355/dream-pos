document.addEventListener("DOMContentLoaded", function () {
  
  const form = document.getElementById("add-product-form");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Create FormData object
    const formData = new FormData(this);
    const productname = $('#product-name').val()
    const price = $('#price').val()
    const category_id  = $('#category-id').val()
    const size = $('#size').val();

    if(productname === ''){
      toastr.error('Enter product name')
      return
    }
    if(category_id === 'Choose Category' ){
      toastr.error('Please Select Category name')
      return
    }
    if(size === 'Select Size' || size === ''){
      toastr.error('Select size')
      return
    }
    if(isNaN(price) || price === ''){
      toastr.error('Enter product price (numbers)')
      return
    }

    // Send AJAX request
    fetch("php/add-product-process.php", {
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
          // $('#category-id').empty();
          // $('#size').empty()
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