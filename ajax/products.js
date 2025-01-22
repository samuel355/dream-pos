// Update your DOMContentLoaded event listener
document.addEventListener("DOMContentLoaded", function () {

  
  // Add styles to size select element
  const sizeSelect = document.getElementById("size");
  const categorySelect = document.getElementById("category-id");

  if (sizeSelect) {
    sizeSelect.style.width = "100%";
    sizeSelect.style.height = "40px";
    sizeSelect.style.fontSize = "16px";
    sizeSelect.style.padding = "5px";
    sizeSelect.style.border = "1px solid #ccc";
    sizeSelect.style.borderRadius = "5px";
  }

  if (categorySelect) {
    categorySelect.style.width = "100%";
    categorySelect.style.height = "40px";
    categorySelect.style.fontSize = "16px";
    categorySelect.style.padding = "5px";
    categorySelect.style.border = "1px solid #ccc";
    categorySelect.style.borderRadius = "5px";
  }

  const form = document.getElementById("add-product-form");
  if (form === null) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Create FormData object
    const formData = new FormData(this);
    const productname = $("#product-name").val();
    const price = $("#price").val();
    const category_id = $("#new-category-id").val();
    const size = $("#new-size").val();

    if (productname === "") {
      toastr.error("Enter product name");
      return;
    }
    if (category_id === "Choose Category") {
      toastr.error("Please Select Category name");
      return;
    }
    if (size === "Select Size" || size === "") {
      toastr.error("Select size");
      return;
    }
    if (isNaN(price) || price === "") {
      toastr.error("Enter product price (numbers)");
      return;
    }

    // Send AJAX request
    fetch("php/add-product-process.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        if (data.status === "success") {
          toastr.success(data.message);
          form.reset();
          $("#create-product-mal").modal("hide");
          window.location.reload();
          toastr.success('Product Created successfully')
          document.getElementById("preview").style.display = "none";
        }
        if(data.status === 'error'){
          toastr.error(data.message)
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        toastr.error("An error occurred. Please try again..");
      });
  });
});

loadProducts();

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
  const table = $("#productsTable");

  // Destroy existing DataTable if it exists
  if ($.fn.DataTable.isDataTable(table)) {
    table.DataTable().destroy();
  }

  // Clear the table body
  const tbody = table.find("tbody");
  tbody.empty();

  // Add new data
  products.forEach((product, i) => {
    const imageSrc = product.image !== 'php/' ? product.image : "../assets/img/boba/boba-c.png";

    tbody.append(`
          <tr>
            <td>${i +1}</td>
              <td class="productimgname">
                  <a href="javascript:void(0);" class="product-img">                      
                      <img src="${imageSrc}" alt="${product.name}">
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
      `);
  });

  // Initialize DataTable with options
  table.DataTable({
    responsive: true,
    ordering: true,
    searching: true,
    paging: true,
    pageLength: 10,
    dom: "Bfrtip",
    buttons: ["copy", "csv", "excel", "pdf", "print"],
    language: {
      emptyTable: "No products available",
    },
    columnDefs: [
      {
        targets: 0, // Image column
        orderable: false,
      },
      {
        targets: -1, // Action column
        orderable: false,
      },
    ],
  });
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
function editProductModal(product_id) {
  const formData = new FormData();
  formData.append("product_id", product_id);

  fetch("php/get-single-product.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        $("#edit-product-modal").modal("show");
        document.getElementById("product-name-edt").value = data.product.name;
        document.getElementById("product-image-preview").src =
          data.product.image;
        document.getElementById("product-price").value = data.product.price;

        // Store product ID for update
        document.getElementById("edit-product-form").dataset.productId =
          data.product.id;
        document.getElementById("product_size").value = data.product.size;
        document.getElementById("product_category_id").value =
          data.product.category_name;
      }
    });
}

