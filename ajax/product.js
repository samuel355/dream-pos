// Update your DOMContentLoaded event listener
document.addEventListener("DOMContentLoaded", function () {

  // Add styles to size select element
  const sizeSelect = document.getElementById('size');
  const categorySelect = document.getElementById('category-id');
  
  if (sizeSelect) {
    sizeSelect.style.width = '100%';
    sizeSelect.style.height = '40px'; 
    sizeSelect.style.fontSize = '16px';
    sizeSelect.style.padding = '5px';
    sizeSelect.style.border = '1px solid #ccc';
    sizeSelect.style.borderRadius = '5px';
  }

  if (categorySelect) {
    categorySelect.style.width = '100%';
    categorySelect.style.height = '40px'; 
    categorySelect.style.fontSize = '16px';
    categorySelect.style.padding = '5px';
    categorySelect.style.border = '1px solid #ccc';
    categorySelect.style.borderRadius = '5px';
  }

  const form = document.getElementById("add-product-form");
  if(form === null) return;

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
          loadProducts();
          document.getElementById("preview").style.display = "none";
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        toastr.error("An error occurred. Please try again..")
      });
  });

  // Handle Update Category
  // document
  //   .getElementById("edit-category-form")
  //   .addEventListener("submit", function (e) {
  //     e.preventDefault();
  //     const categoryId = this.dataset.categoryId;
  //     const category_name = $("#category-name-edt").val();
  //     const formData = new FormData(this);
  //     formData.append("category_id", categoryId);
  //     if (category_name === "") {
  //       return toastr.error("Enter Category Name");
  //     }
  //     fetch("php/update-category.php", {
  //       method: "POST",
  //       body: formData,
  //     })
  //       .then((response) => response.json())
  //       .then((data) => {
  //         if (data.status === "success") {
  //           // Close modal
  //           $("#edit-category-modal").modal("hide");
  //           // Show success message
  //           loadProducts();
  //           toastr.success("Category updated successfully!");
  //         } else {
  //           alert("Error updating category: " + data.message);
  //         }
  //       })
  //       .catch((error) => {
  //         console.error("Error:", error);
  //         alert("An error occurred while updating the category");
  //       });
  //   });
});



function loadProducts() {
  fetch("php/fetch-all-products.php")
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        displayTableProducts(data.products);
      } else {
        console.error("Error loading products:", data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

// Display products in tabular form
function displayTableProducts(products) {
  const productsList = document.querySelector(".products-content");
  if (productsList === null) return;

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
              })" class="me-3" href="javascript:void(0);">
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

//Delete Product
function deleteProduct(productId) {

  const formData = new FormData();
  formData.append("product_id", productId);
  if (!confirm("Are you sure you want to delete this product")) return;

  fetch("php/delete-product.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        loadProducts();
        toastr.success("Product Deleted Successfully");
      }
      if (data.status === "error") {
        toastr.error(data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

// Open Edit Category Modal with category Details
function editCategoryModal(product_id) {
  const formData = new FormData();
  formData.append("product_id", product_id);

  fetch("php/get-single-category.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        $("#edit-product-modal").modal("show");
        document.getElementById("category-name-edt").value = data.category.name;
        document.getElementById("cat-preview").src = data.category.image;

        //Store category ID for update
        document.getElementById("edit-category-form").dataset.categoryId =
          categoryId;
      }
    });
}


loadProducts();