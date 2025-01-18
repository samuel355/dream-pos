function loadProducts() {
  fetch("php/fetch-all-products.php")
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        const productsList = document.querySelector(".products-content"); //table

        //table of products
        if (productsList !== null) {
          displayTableProducts(data.products);
          return;
        }
      } else {
        console.error("Error loading products:", data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

// Display products in tabular form
function displayTableProducts(products) {
  const productsList = document.querySelector(".products-content");
  if(productsList === null) return;

  let html = "";
  products.forEach((product) => {
    html += `
          <tr>
            <td class="productimgname">
              <a href="javascript:void(0);" class="product-img">
                <img src="${product.image}" alt="${product.name}">
              </a>
            </td>
            <td>${product.name}</td>
            <td>${product.category_name}</td>
            <td>${product.price}</td>
            <td>${product.size}</td>
            <td>${product.created_by ?? "Admin"}</td>
            <td>
              <a class="me-3" onclick="editProductModal(${product.id})">
                <img src="assets/img/icons/edit.svg" alt="img">
              </a>

              <a onclick="deleteProduct(${
                product.id
              })" class="me-3 confirm-text" href="javascript:void(0);">
                <img src="assets/img/icons/delete.svg" alt="img">
              </a>
            </td>
          </tr>
      `;
  });

  if (products.length === 0) {
    html =
      '<li class="notification-message"><div class="media d-flex"><div class="media-body flex-grow-1"><p class="text-center">No Products</p></div></div></li>';
  }

  productsList.innerHTML = html;
}


// Open Edit Category Modal with category Details
function editCategoryModal(categoryId) {
  const formData = new FormData();
  formData.append("category_id", categoryId);

  fetch("php/get-single-category.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        $("#edit-category-modal").modal("show");
        document.getElementById("category-name-edt").value = data.category.name;
        document.getElementById("cat-preview").src = data.category.image;

        //Store category ID for update
        document.getElementById("edit-category-form").dataset.categoryId =
          categoryId;
      }
    });
}

// Update your DOMContentLoaded event listener
document.addEventListener("DOMContentLoaded", function () {
  // Load categories first
  loadProducts();

  // Load initial cart
  updateCart();

  //Create new category
  const form = document.getElementById("add-category-form");
  //Add category
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Create FormData object
    const formData = new FormData(this);
    const categoryname = $("#category-name").val();

    if (categoryname === "") {
      toastr.error("Please Enter category name");
      return;
    }

    // Send AJAX request
    fetch("php/add-category-process.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        if (data.status === "success") {
          $("#create-modal").modal("hide");

          loadProducts();
          toastr.success(data.message);
          form.reset();
          document.getElementById("preview").style.display = "none";
        } else {
          toastr.error(data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        toastr.error("An error occurred. Please try again..");
      });
  });

  // Handle Update Category
  document
    .getElementById("edit-category-form")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const categoryId = this.dataset.categoryId;
      const category_name = $("#category-name-edt").val();
      const formData = new FormData(this);
      formData.append("category_id", categoryId);

      if (category_name === "") {
        return toastr.error("Enter Category Name");
      }

      fetch("php/update-category.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            // Close modal
            $("#edit-category-modal").modal("hide");

            // Show success message
            loadProducts();
            toastr.success("Category updated successfully!");
          } else {
            alert("Error updating category: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("An error occurred while updating the category");
        });
    });
});