function loadCategories() {
  fetch("php/get-categories.php")
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        const categoriesList = document.querySelector(".categories-content"); //table
        const categoriesContainer = document.getElementById(
          "categories-container"
        ); //pos

        //table categories
        if (categoriesList !== null) {
          displayTableCategories(data.categories);
          return;
        }

        //pos categories
        if (categoriesContainer !== null) {
          displayCategories(data.categories);
          return;
        }
      } else {
        console.error("Error loading categories:", data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

// Display categories in tabular form
function displayTableCategories(categories) {
  const categoriesList = document.querySelector(".categories-content");

  let html = "";
  categories.forEach((category) => {
    const imageSrc =
      category.image !== "php/" ? category.image : "assets/img/boba/boba-c.png";
    html += `
          <tr>
            <td class="productimgname">
              <a href="javascript:void(0);" class="product-img">
                <img src="${imageSrc}" alt="${category.name}">
              </a>
            </td>
            <td>${category.name}</td>
            <td>${category.created_by ?? "Admin"}</td>
            <td>
              <a class="me-3" onclick="editCategoryModal(${category.id})">
                <img src="assets/img/icons/edit.svg" alt="img">
              </a>

              <a onclick="deleteCategory(${
                category.id
              })" class="me-3 confirm-text" href="javascript:void(0);">
                <img src="assets/img/icons/delete.svg" alt="img">
              </a>
            </td>
          </tr>
      `;
  });

  if (categories.length === 0) {
    html =
      '<li class="notification-message"><div class="media d-flex"><div class="media-body flex-grow-1"><p class="text-center">No new notifications</p></div></div></li>';
  }

  categoriesList.innerHTML = html;
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
        const store_cart_id = document.getElementById("edit-category-form");
        if (store_cart_id) {
          store_cart_id.dataset.categoryId = categoryId;
        }
      }
    });
}

// Function to display categories on POS page
function displayCategories(categories) {
  const categoriesContainer = document.getElementById("categories-container");
  let html = "";

  if (categories.length <= 0) {
    categoriesContainer.textContent = "No Products available yet";
    return;
  }
  // First category should be active by default
  categories.forEach((category, index) => {
    const imageSrc =
      cat.image !== "php/" ? dt.image : "assets/img/boba/boba-c.png";
    html += `
          <li class="${index === 0 ? "active" : ""}" id="category-${
      category.id
    }" 
              data-category-id="${category.id}" >
              <a href="javascript:void(0);"> 
              <img style="width:100%; height:60px; object-fit: contain; border-radius: 20px" src="${imageSrc}" alt="${
      category.name
    }"> </a>
              <h6 style="margin-bottom: 8px" class="text-center">${
                category.name
              }</h6>
          </li>
      `;
  });

  categoriesContainer.innerHTML = html;

  // Initialize click events for categories
  initializeCategoryEvents();

  // Load products for first category by default
  if (categories.length > 0) {
    const firstCategoryId = categories[0].id;
    const tabContent = document.querySelector(
      `.tab_content[data-tab="category-${firstCategoryId}"]`
    );
    if (tabContent) {
      // Remove active class from all tab contents
      document.querySelectorAll(".tab_content").forEach((tab) => {
        tab.classList.remove("active");
      });
      // Add active class to first category's tab content
      tabContent.classList.add("active");
    }
    loadProducts(firstCategoryId);
  }
}

function initializeCategoryEvents() {
  const categoryElements = document.querySelectorAll(
    "#categories-container li"
  );

  categoryElements.forEach((categoryElement) => {
    categoryElement.addEventListener("click", function (e) {
      e.preventDefault();

      // Remove active class from all categories
      categoryElements.forEach((el) => el.classList.remove("active"));

      // Add active class to clicked category
      this.classList.add("active");

      // Get category ID
      const categoryId = this.dataset.categoryId;
      //console.log("Selected category ID:", categoryId); // Debug log

      // Remove active class from all tab contents
      document.querySelectorAll(".tab_content").forEach((tab) => {
        tab.classList.remove("active");
      });

      // Add active class to corresponding tab content
      const correspondingTab = document.querySelector(
        `.tab_content[data-tab="category-${categoryId}"]`
      );
      if (correspondingTab) {
        correspondingTab.classList.add("active");
      }

      // Load products for the selected category
      loadProducts(categoryId);
    });
  });
}

// Function to load products by category
function loadProducts(categoryId) {
  const productsContainer = document.querySelector(".tabs_container");

  if (productsContainer === null) return;

  // Show loading state
  const loadingHtml = `
      <div class="tab_content active" data-tab="category-${categoryId}">
          <div class="row">
              <div class="col-12 text-center">
                  <p>Loading products...</p>
              </div>
          </div>
      </div>
  `;
  productsContainer.innerHTML = loadingHtml;

  // Fetch products with category ID as parameter
  fetch(`php/get-products.php?category_id=${categoryId}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        // Filter products by category ID before displaying
        const filteredProducts = data.data.filter(
          (product) =>
            product.category_id === categoryId ||
            parseInt(product.category_id) === parseInt(categoryId)
        );
        displayProducts(filteredProducts, categoryId);
      } else {
        throw new Error(data.message || "Failed to load products");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      productsContainer.innerHTML = `
              <div class="tab_content active" data-tab="category-${categoryId}">
                  <div class="row">
                      <div class="col-12 text-center">
                          <p>Error loading products. Please try again.</p>
                      </div>
                  </div>
              </div>
          `;
    });
}

// Function to display products
function displayProducts(products, categoryId) {
  const productsContainer = document.querySelector(".tabs_container");
  let html = `<div class="tab_content active" data-tab="category-${categoryId}"><div class="row">`;

  if (!products || products.length === 0) {
    html += `
          <div class="col-12 text-center">
              <p>No products found in this category.</p>
          </div>
      `;
  } else {
    products.forEach((product) => {
      const imageSrc =
        product.image !== null
          ? `php/${product.image}`
          : "../assets/img/boba/boba-c.png";
      if (
        product.category_id === "1" ||
        product.category_id === "3" ||
        product.category_id === "5"
      ) {
        html += `
              <div class="col-sm-2 col-md-6 col-lg-3 col-xl-3">
                  <div class="product-info default-cover card" onclick="addToCart(${
                    product.id
                  })">

                      <a href="javascript:void(0);" class="img-bg">
                          <img src="${imageSrc}" 
                              alt="${product.name}"
                              onerror="this.src='assets/img/products/default.png'"
                              style="width: 85%; height: 15vh; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); object-fit: cover; margin-bottom:6px"/>
                          <span><i data-feather="check" class="feather-16"></i></span>
                      </a>
                      <small class="cat-name mt-5"><a href="javascript:void(0);">${
                        product.category_name
                      }</a></small>
                      <h4 style="margin-top: 8px" class="product-name"><a href="javascript:void(0);">${
                        product.name
                      }</a></h4>
                      <div class="d-flex align-items-center justify-content-between price">
                          <span>
                          
                          </span>
                          <p>GHS ${parseFloat(product.price).toFixed(2)}</p>
                      </div>
                  </div>
              </div>
          `;
      } else {
        html += `
              <div class="col-sm-2 col-md-6 col-lg-3 col-xl-3">
                  <div class="product-info default-cover card" onclick="addToCart(${
                    product.id
                  })">
                      <small class="cat-name mt-5"><a href="javascript:void(0);">${
                        product.category_name
                      }</a></small>
                      <h4 style="margin-top: 8px" class="product-name"><a href="javascript:void(0);">${
                        product.name
                      }</a></h4>
                      <div class="d-flex align-items-center justify-content-between price">
                          <span>
                          
                          </span>
                          <p>GHS ${parseFloat(product.price).toFixed(2)}</p>
                      </div>
                  </div>
              </div>
          `;
      }
    });
  }

  html += "</div></div>";
  productsContainer.innerHTML = html;

  // Reinitialize Feather icons if you're using them
  if (typeof feather !== "undefined") {
    feather.replace();
  }
}

// Function to add product to cart
function addToCart(productId, quantity = 1) {
  const formData = new FormData();
  formData.append("product_id", productId);
  formData.append("quantity", quantity);

  fetch("php/add-to-cart.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        toastr.success("Product added to cart");
        updateCart();
      }
      if (data.status === "info") {
        toastr.info(data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

//Fetch cart items
function updateCart() {
  fetch("php/get-cart.php")
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        const cartData = data.data;

        // Get container elements
        const leftContainer = document.querySelector(".product-right-block");
        const rightContainer = document.querySelector(".order-right-block");
        const valuesContainer = document.querySelector(".setvalue");
        const orderBtn = document.querySelector(".order-btn-container");

        // Adjust layout based on cart items
        if (cartData.items.length === 0) {
          if (valuesContainer) {
            valuesContainer.style.display = "none";
          }
          if (orderBtn) {
            orderBtn.style.display = "none";
          }
          if (leftContainer) {
            leftContainer.classList.remove("col-lg-8");
            leftContainer.classList.add("col-lg-12");
          }
          if (rightContainer) {
            rightContainer.style.display = "none";
          }
        } else {
          if (valuesContainer) {
            valuesContainer.style.display = "block";
          }
          if (orderBtn) {
            orderBtn.style.display = "block";
          }
          if (leftContainer) {
            leftContainer.classList.remove("col-lg-12");
            leftContainer.classList.add("col-lg-8");
          }
          if (rightContainer) {
            rightContainer.style.display = "block";
          }
        }

        // Display cart items
        displayCart(cartData);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}
// Function to display cart
function displayCart(cartData) {
  const cartContainer = document.querySelector(".product-wrap");
  if (cartContainer === null) return;

  const totalItems = document.querySelector(".count-items");
  const subtotalElement = document.querySelector(".cart-subtotal");
  const totalElement = document.querySelector(".cart-total-items");
  const checkoutTotal = document.querySelector(".cart-total-amount");
  const checkoutCheckout = document.querySelector(".cart-total-checkout");

  // Update total items
  totalItems.textContent = `  ${cartData.items.length}`;

  // Display cart items
  let html = "";

  if (cartData.items.length === 0) {
    html = '<p class="text-center">Your cart is empty</p>';
  } else {
    cartData.items.forEach((item) => {
      const imageSrc =
        item.image !== null
          ? `../php/${item.image}`
          : "../assets/img/boba/boba-c.png";
      if (
        item.category_id === "1" ||
        item.category_id === "3" ||
        item.category_id === "5"
      ) {
        html += `
                <div class="product-list d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center product-info" data-bs-toggle="modal" data-bs-target="#products">
                        <a href="javascript:void(0);" class="img-bg">
                            <img src="${imageSrc}" alt="${
          item.name
        }" style="width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); object-fit: cover;">
                        </a>
                        <div class="info">
                            <h6><a href="javascript:void(0);">${
                              item.name
                            }</a></h6>
                            <p>GHS ${item.price}</p>
                            <p id="selected-boba-size" style="margin-top: -6px">${
                              item.size
                            }</p>
                        </div>
                    </div>

                    <div class="increment-decrement">
                        <div class="input-groups">
                            <input onclick="updateQuantity(${item.cart_id}, ${
          item.quantity - 1
        })" type="button" value="-"
                                class="button-minus dec button">
                            <input type="text" name="child" value="${
                              item.quantity
                            }" readonly
                                class="quantity-field">
                            <input onclick="updateQuantity(${item.cart_id}, ${
          item.quantity + 1
        })" type="button" value="+"
                            class="button-plus inc button ">
                        </div>
                    </div>

                    <div class="d-flex align-items-center action">
                        <a data-bs-toggle="modal" id="cart_id" data-id="${
                          item.cart_id
                        }" data-bs-target="#change-boba-size-modal" href="javascript:void(0);" onclick="openChangeSizeModal(${
          item.cart_id
        })" class="confirm-text">
                            <img src="assets/img/icons/edit.svg" alt="Change Size">
                        </a>
                    </div>

                    <div class="d-flex align-items-center action">
                        <a href="javascript:void(0);" 
                          onclick="deleteCartItem(${item.cart_id})"
                          class="confirm-text">
                            <img src="assets/img/icons/delete-2.svg" alt="Delete">
                        </a>
                    </div>
                </div>
            `;
      } else {
        html += `
              <div class="product-list d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center product-info" data-bs-toggle="modal" data-bs-target="#products">
                      <div class="info">
                          <h6><a href="javascript:void(0);">${
                            item.name
                          }</a></h6>
                          <p>GHS ${item.price}</p>
                      </div>
                  </div>

                  <div class="increment-decrement">
                      <div class="input-groups">
                          <input onclick="updateQuantity(${item.cart_id}, ${
          item.quantity - 1
        })" type="button" value="-"
                              class="button-minus dec button">
                          <input type="text" name="child" value="${
                            item.quantity
                          }" readonly
                              class="quantity-field">
                          <input onclick="updateQuantity(${item.cart_id}, ${
          item.quantity + 1
        })" type="button" value="+"
                          class="button-plus inc button ">
                      </div>
                  </div>
                  <div class="d-flex align-items-center action">
                      <a href="javascript:void(0);" 
                        onclick="deleteCartItem(${item.cart_id})"
                        class="confirm-text">
                          <img src="assets/img/icons/delete-2.svg" alt="Delete">
                      </a>
                  </div>
              </div>
          `;
      }
    });
  }

  cartContainer.innerHTML = html;

  // Update totals
  subtotalElement.textContent = ` GHS. ${cartData.subtotal.toFixed(2)}`;
  totalElement.textContent = `${cartData.total_items}`;
  checkoutTotal.textContent = ` GHS ${cartData.total.toFixed(2)}`;
  checkoutCheckout.textContent = ` GHS ${cartData.total.toFixed(2)}`;

  //Print preview button
  const createOrderBtn = document.querySelector(".order-btn-container");
  createOrderBtn.onclick = () => {
    createOrder(cartData);
  };
}

//Change boba size [medium or Large]
function changeBobaSize(cartId) {
  const selectSize = document.getElementById("change-boba-size");
  sizeValue = selectSize.value;

  const formData = new FormData();
  formData.append("boba_size", sizeValue);
  formData.append("cart_id", cartId);

  console.log(`Sending: boba-size=${sizeValue}, cart_id=${cartId}`);
  if (sizeValue === "Change Size") {
    return toastr.error("Select Large or Medium");
  }

  fetch("php/update-boba-size.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        console.log(data);

        updateCart();
        selectSize.innerHTML = `
          <option value="Change Size">Change Size</option>
          <option value="Medium">Medium</option>
          <option value="Large">Large</option>
        `;
      } else {
        toastr.error(data.message || "Error updating size.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("An error occurred while updating the size.");
    });
}

// Function to delete cart item
function deleteCartItem(cartId) {
  if (confirm("Are you sure you want to remove this item?")) {
    const formData = new FormData();
    formData.append("cart_id", cartId);

    fetch("php/delete-cart-item.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          updateCart(); // Refresh cart display
        } else {
          alert(data.message);
        }
      })
      .catch((error) => console.error("Error:", error));
  }
}

// Function to update quantity
function updateQuantity(cartId, newQuantity) {
  if (newQuantity < 1) {
    toastr.error("Quantity cannot be less than 1");
    return;
  }

  const formData = new FormData();
  formData.append("cart_id", cartId);
  formData.append("quantity", newQuantity);

  fetch("php/update-cart.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        updateCart(); // Refresh cart display
      }
      if (data.status === "info") {
        toastr.info(data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

// Function to clear cart
function clearCart() {
  if (confirm("Are you sure you want to clear your cart?")) {
    fetch("php/clear-cart.php", {
      method: "POST",
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          updateCart(); // Refresh cart display
        } else {
          alert(data.message);
        }
      })
      .catch((error) => console.error("Error:", error));
  }
}

//Checkout funciton
function createOrder(cartData) {
  const order_btn = document.querySelector(".order-btn-container");
  order_btn.disabled = true;
  order_btn.textContent = "Processing...";
  //Save details to cart before previewing cart data to print.
  const customerName = document.getElementById("customer-name").value;
  const customerContact = document.getElementById("customer-contact").value;

  if (customerName === "") {
    toastr.error("Enter customer name");
    order_btn.textContent = "Order now";
    return;
  }
  if (customerName.length < 3) {
    toastr.error("Add your full name");
    order_btn.textContent = "Order now";
    return;
  }
  if (customerContact === "") {
    toastr.error("Enter your contact");
    order_btn.textContent = "Order now";
    return;
  }
  const invoiceNumber = generateReceiptNumber(customerName);

  const formData = new FormData();
  formData.append("customer_name", customerName);
  formData.append("customer_phone", customerContact);
  formData.append("invoice_number", invoiceNumber);

  fetch("php/process-order.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        previewReceipt(cartData, invoiceNumber, customerName, customerContact);
        toastr.success(
          "Your order is created successfully. Print your invoice"
        );
        document.getElementById("customer-name").value = "";
        document.getElementById("customer-contact").value = "";
      } else {
        alert("Error processing your order: " + data.message);
        order_btn.textContent = "Order now";
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("Error processing order");
      order_btn.textContent = "Order now";
    });
}
//Function to print preview
function previewReceipt(
  cartData,
  invoiceNumber,
  customerName,
  customerContact
) {
  const receiptWindow = window.open("", "_blank", "width=400,height=600");

  let html = `
      <!DOCTYPE html>
      <html>
      <head>
          <title>Receipt Preview</title>
          <style>
            *{
              margin: 0;
              padding: 4px;
              box-sizing: border-box;
            }
              body {
                  font-family: monospace;
                  padding: 20px;
                  max-width: 400px;
                  margin: 0 auto;
              }
              .header {
                  text-align: center;
                  margin-bottom: 20px;
              }
              .items-table {
                  width: 100%;
                  border-collapse: collapse;
                  margin: 20px 0;
              }
              .items-table th {
                  border-bottom: 1px solid #000;
                  padding: 5px;
                  text-align: left;
              }
              .items-table td {
                  padding: 5px;
                  text-align: left;
              }
              .totals {
                  font-weight: bold;
                  margin-top: 20px;
                  text-align: right;
              }
              .footer {
                  text-align: center;
                  margin-top: 20px;
              }
              @media print {
                  .no-print {
                      display: none;
                  }
                  body {
                      width: 100%;
                      margin: 0;
                      padding: 10px;
                  }
              }
          </style>
      </head>
      <body>
          <div class="header">
              <h2>POPSY BUBBLE TEA SHOP</h2>
              <p>Ayeduase New Site - </p>
              <small>Close to Liendaville Hostel</small>
              <p>Tel: 0530975528</p>
              <p>Date: ${new Date().toLocaleString()}</p>
              <p>INV #: ${invoiceNumber}</p>
              <p>Customer: ${customerName}</p>
              <p>Contact: ${customerContact}</p>
              <p>Served BY: ${cartData.items[0].created_by}</p>
          </div>
          
          <table class="items-table">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Item</th>
                      <th>Price</th>
                      <th>Qty</th>
                      <th>Total</th>
                  </tr>
              </thead>
              <tbody>
  `;

  cartData.items.forEach((item, index) => {
    const price = parseFloat(item.price);
    const quantity = parseInt(item.quantity);
    const total = price * quantity;

    html += `
          <tr>
              <td>${index + 1}</td>
              <td>${item.name}</td>
              <td>${price.toFixed(2)}</td>
              <td>${quantity}</td>
              <td>${total.toFixed(2)}</td>
          </tr>
      `;
  });

  const subtotal = parseFloat(cartData.subtotal);
  const total = parseFloat(cartData.total);

  html += `
              </tbody>
          </table>
          
          <div class="totals">
              <p>Subtotal: GHS ${subtotal.toFixed(2)}</p>
              <p>Total: GHS ${total.toFixed(2)}</p>
          </div>
          
          <div class="footer">
              <p>Thank you for your purchase!</p>
              <p>Please come again</p>
          </div>

          <div class="no-print" style="text-align: center; margin-top: 20px;">
              <button onclick="window.print()" class="print-invoice" 
                      style="padding: 10px 20px;">Print Receipt</button>
          </div>
      </body>
      </html>
  `;

  receiptWindow.document.write(html);
  receiptWindow.document.close();

  const order_btn = document.querySelector(".order-btn-container");
  receiptWindow.onclose = function () {
    clearCart(); // Clear the cart
    order_btn.disabled = false;
  };

  receiptWindow.onafterprint = function () {
    receiptWindow.close();
    clearCart(); // Clear the cart
    order_btn.disabled = false;
  };
}

// Generate Invoice
function generateReceiptNumber(name) {
  const prefix = "INV-";
  const namePrefix = name
    .split(" ")
    .map((name) => name.substring(0, 2).toUpperCase())
    .join("");
  const random = String(Math.floor(Math.random() * 90000) + 10000);

  return prefix + namePrefix + random;
}

//Delete Category
function deleteCategory(categoryId) {
  const formData = new FormData();
  formData.append("category_id", categoryId);
  if (
    !confirm(
      "Deleting this category will delete all products associated with it. Do you want to proceded"
    )
  )
    return;

  fetch("php/delete-category.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        loadCategories();
        toastr.success("Category Deleted Successfully");
      }
      if (data.status === "error") {
        toastr.error(data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

// Update your DOMContentLoaded event listener
document.addEventListener("DOMContentLoaded", function () {
  // Load categories first
  loadCategories();

  // Load initial cart
  updateCart();

  //Create new category
  const form = document.getElementById("add-category-form");
  //Add category
  if (form) {
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

            loadCategories();
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
  }

  // Handle Update Category
  const edit_category_form_submit =
    document.getElementById("edit-category-form");

  if (edit_category_form_submit) {
    edit_category_form_submit.addEventListener("submit", function (e) {
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
            loadCategories();
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
  }
});

let currentCartId = null;

function openChangeSizeModal(cartId) {
  document.getElementById("cart_id_input").value = cartId;
}

function updateSize() {
  const cartId = document.getElementById("cart_id_input").value;
  const newSize = document.getElementById("boba-new-size").value;

  // Create FormData object
  const formData = new FormData();
  formData.append("cart_id", cartId);
  formData.append("new_size", newSize);
  formData.append("action", "update_size");

  // Show loading indicator
  document.querySelector(
    "#change-boba-size-modal button.btn-primary"
  ).innerHTML = "Updating...";

  // Make AJAX call
  fetch("php/update-boba-size.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        // Close modal
        $("#change-boba-size-modal").modal("hide");
        toastr.success("Size updated successfully!");
        updateCart();
      } else {
        toastr.error(data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("An error occurred while updating the size.");
    })
    .finally(() => {
      // Reset button text
      document.querySelector(
        "#change-boba-size-modal button.btn-primary"
      ).innerHTML = "Update Size";
    });
}
