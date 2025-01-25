document.addEventListener("DOMContentLoaded", function () {
  // Load product size first
  loadProductPricing();

  //Create new product size
  const form = document.getElementById("add-product-pricing-form");

  //Add product size
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      // Create FormData object
      const formData = new FormData(this);
      const size_name = $("#size-name").val();
      const category_id = $("#category-id").val();
      const price = $("#price").val();

      if (category_id === "Choose Category" || category_id === "") {
        toastr.error("Select  Product Category");
        return;
      }

      if (size_name === "" || size_name === "Select Size") {
        toastr.error("Please Enter the size name: Small, Medium, Large, etc.");
        return;
      }

      if (isNaN(price) || price === "") {
        toastr.error("Enter the correct amount");
        return;
      }

      // Send AJAX request
      fetch("php/add-product-pricing.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          return response.json();
        })
        .then((data) => {
          if (data.status === "success") {
            form.reset();
            $("#pricing-modal").modal("hide");
            loadProductPricing();
            toastr.success(data.message);
          } else {
            toastr.error(data.message);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          toastr.error("An error occurred. Please try again..");
        });
    });
  }

  const editForm = document.getElementById("edit-product-pricing-form");
  if (editForm) {
      editForm.addEventListener("submit", function(e) {
          e.preventDefault();

          const priceId = document.getElementById("edit-price-id").value;
          const newPrice = document.getElementById("edit-price").value;

          if (isNaN(newPrice) || newPrice === "") {
              toastr.error("Please enter a valid price");
              return;
          }

          const formData = new FormData(this);

          fetch("php/update-product-pricing.php", {
              method: "POST",
              body: formData
          })
          .then(response => response.json())
          .then(data => {
              if (data.status === "success") {
                  $("#edit-pricing-modal").modal("hide");
                  loadProductPricing();
                  toastr.success(data.message);
              } else {
                  toastr.error(data.message);
              }
          })
          .catch(error => {
              console.error("Error:", error);
              toastr.error("An error occurred while updating the price");
          });
      });
  }
});

function loadProductPricing() {
  fetch("php/get-product-pricing.php")
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        displayProductPricings(data.product_pricing);
      } else {
        console.error("Error loading data:", data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

function displayProductPricings(data) {
  const pricingList = document.querySelector(".product-pricing-content");
  if (pricingList === null) return;

  let html = "";
  data.forEach((dt, i) => {
    const imageSrc = dt.image !== 'php/' ? dt.image : 'assets/img/boba/boba-c.png'
    html += `
          <tr>
              <td>${i + 1}</td> 
              <td class="productimgname">
                <a href="javascript:void(0);" class="product-img">
                  <img src="${imageSrc}" alt="${dt.name}">
                </a>
                <span>${dt.category_name}</span>
              </td>
              <td>${dt.size_name}</td>
              <td>${dt.price}</td>
              <td>
                    <a class="me-3" href="javascript:void(0);" onclick="editProductPrice(${dt.id})">
                        <img src="assets/img/icons/edit.svg" alt="img">
                    </a>
                    <a onclick="deleteProductPrice(${dt.id})" class="me-3 confirm-text" href="javascript:void(0);">
                        <img src="assets/img/icons/delete.svg" alt="img">
                    </a>
                </td>
          </tr>
      `;
  });

  if (data.length === 0) {
    html =
      '<li class="notification-message"><div class="media d-flex"><div class="media-body flex-grow-1"><p class="text-center">No new notifications</p></div></div></li>';
  }

  pricingList.innerHTML = html;
}

//Delete Category Price
function deleteProductPrice(id) {
  const formData = new FormData();
  formData.append("price_id", id);
  if (!confirm("Are you sure you want to delete?. Do you want to proceded"))
    return;

  fetch("php/delete-product-pricing.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        loadProductPricing();
        toastr.success("Pricing Deleted Successfully");
      }
      if (data.status === "error") {
        toastr.error(data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

function editProductPrice(priceId) {
  // Fetch pricing details
  const formData = new FormData();
  formData.append("price_id", priceId);

  fetch("php/get-single-product-pricing.php", {
      method: "POST",
      body: formData
  })
  .then(response => response.json())
  .then(data => {
      if (data.status === "success") {
          showEditPricingModal(data.pricing);
      } else {
          toastr.error(data.message);
      }
  })
  .catch(error => {
      console.error("Error:", error);
      toastr.error("Error fetching pricing details");
  });
}

function showEditPricingModal(pricing) {
  // Populate the edit form
  const search_price = document.getElementById("edit-price-id");
  const search_cate_nam = document.getElementById("edit-category-name")
  const search_size = document.getElementById("edit-size-name");


  if(search_price){
    search_price.value = pricing.id
  }
  if(search_cate_nam){
    search_cate_nam.value = pricing.category_name;
  }
  if(search_size)
  
  .value = pricing.size_name;
  document.getElementById("edit-price").value = pricing.price;

  // Show the modal
  const modal = new bootstrap.Modal(document.getElementById("edit-pricing-modal"));
  modal.show();
}