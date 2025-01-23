document.addEventListener("DOMContentLoaded", function () {
  fetchAllProducts();

  //Add Product
  document
    .getElementById("add-product-form")
    .addEventListener("submit", addProduct);

  //Edit Product
  document
    .getElementById("edit-product-form")
    .addEventListener("submit", editProduct);
});

function fetchAllProducts() {
  fetch("php/fetch-all-products.php")
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        showProductsInTable(data.products);
      }
      if (data.status === "error") {
        toastr.error(data.message);
      }
    });
}

function showProductsInTable(products) {
  const table = $("#productsTable");

  // Destroy existing DataTable if it exists
  if ($.fn.DataTable.isDataTable(table)) {
    table.DataTable().destroy();
  }

  const tbody = table.find("tbody");
  tbody.empty();

  products.forEach((product, i) => {
    const imageSrc =
      product.image !== "php/"
        ? product.image
        : "../assets/img/boba/boba-c.png";

    tbody.append(
      `
        <tr>
          <td>${i + 1}</td>
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
            <a class="me-3" onclick="openEditModal(${product.id})">
              <img src="assets/img/icons/edit.svg" alt="img">
            </a>
            <a class="me-3" onclick="deleteProduct(${product.id})">
              <img src="assets/img/icons/delete.svg" alt="img">
            </a>
          </td>

        </tr>
      `
    );
  });

  //Initialize datatable
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

function addProduct(event) {
  event.preventDefault();

  // FormData object
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
        document.getElementById("add-product-form").reset();
        $("#create-product-modal").modal("hide");
        document.getElementById("preview").style.display = "none";
        fetchAllProducts();
      }
      if (data.status === "error") {
        toastr.error(data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("An error occurred. Please try again..");
    });
}

function openEditModal(productId) {
  const formData = new FormData();
  formData.append("product_id", productId);

  fetch("php/get-single-product.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        const product = data.product;
        const imageSrc =
          product.image !== "php/" || product.iamge !== null
            ? `${product.image}`
            : "assets/img/boba/boba-c.png";

        $("#edit-product-modal").modal("show");

        document.getElementById("edit-product-name").value = product.name;
        document.getElementById("product-image-preview").src = imageSrc;

        //store product ID
        document.getElementById("edit-product-form").dataset.productId =
          product.id;
        document.getElementById("product_category_id").value =
          product.category_name;
      }
      if(data.status === 'error'){
        toastr.error(data.message)
      }
    });
}

function editProduct(event) {
  event.preventDefault();

  const product_name = document.getElementById("edit-product-name").value;
  const productId =
    document.getElementById("edit-product-form").dataset.productId;
  const imageFile = document.getElementById("edit-image").files[0];

  const formData = new FormData();
  formData.append("product_name", product_name);
  formData.append("product_id", productId);

  if (imageFile) {
    formData.append("image", imageFile);
  }

  fetch("php/update-product.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        toastr.success("Product updated successfully");
        $("#edit-product-modal").modal("hide");
        fetchAllProducts();
      } else if (data.status === "error") {
        toastr.error(data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

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
        toastr.success("Product Deleted Successfully");
        fetchAllProducts();
      }
      if (data.status === "error") {
        toastr.error(data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}
