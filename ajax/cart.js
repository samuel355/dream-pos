function loadCategories() {
  fetch("php/get-categories.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        displayCategories(data.data);
      } else {
        console.error("Error loading categories:", data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

// Function to display categories
function displayCategories(categories) {
  const categoriesContainer = document.getElementById("categories-container");
  let html = "";

  // First category should be active by default
  categories.forEach((category, index) => {
    html += `
          <li class="${index === 0 ? "active" : ""}" id="category-${
      category.id
    }" 
              data-category-id="${category.id}">
              <a href="javascript:void(0);"> 
              <img style="width:100%; height:60px; object-fit: contain; border-radius: 20px" src="${
                category.image
              }" alt="${category.name}"> </a>
              <h6 class="text-center">${category.name}</h6>
              <p class="text-center">4 Items</p>
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
      console.log("Selected category ID:", categoryId); // Debug log

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
      html += `
              <div class="col-sm-2 col-md-6 col-lg-3 col-xl-3">
                  <div class="product-info default-cover card" onclick="addToCart(${
                    product.id
                  })">
                      <a href="javascript:void(0);" class="img-bg">
                          <img src="../php/${product.image}" 
                              alt="${product.name}"
                              onerror="this.src='assets/img/products/default.png'"
                              style="width: 85%; height: auto; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); object-fit: cover; margin-bottom:6px"/>
                          <span><i data-feather="check" class="feather-16"></i></span>
                      </a>
                      <h6 class="cat-name mt-4"><a href="javascript:void(0);">${
                        product.category_name
                      }</a></h6>
                      <h6 class="product-name"><a href="javascript:void(0);">${
                        product.name
                      }</a></h6>
                      <div class="d-flex align-items-center justify-content-between price">
                          <span>${product.quantity || "N/A"} ${
        product.unit || "Pcs"
      }</span>
                          <p>GHS ${parseFloat(product.price).toFixed(2)}</p>
                      </div>
                  </div>
              </div>
          `;
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
    })
    .catch((error) => console.error("Error:", error));
}

//Fetch cart items
function updateCart() {
  fetch("php/get-cart.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        console.log(data.data)
        displayCart(data.data);
      }
    })
    .catch((error) => console.error("Error:", error));
}

// Function to display cart
function displayCart(cartData) {
    const cartContainer = document.querySelector('.product-wrap');
    const totalItems = document.querySelector('.count-items');
    const subtotalElement = document.querySelector('.cart-subtotal');
    const totalElement = document.querySelector('.cart-total-items');
    const checkoutTotal = document.querySelector('.cart-total-amount');
    
    // Update total items
    totalItems.textContent = `${cartData.total_items}`;
    
    // Display cart items
    let html = '';
    
    if (cartData.items.length === 0) {
        html = '<p class="text-center">Your cart is empty</p>';
    } else {
        cartData.items.forEach(item => {
            html += `
                <div class="product-list d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center product-info" data-bs-toggle="modal" data-bs-target="#products">
                        <a href="javascript:void(0);" class="img-bg">
                            <img src="../php/${item.image}" alt="${item.name}" style="width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); object-fit: cover;">
                        </a>
                        <div class="info">
                            <h6><a href="javascript:void(0);">${item.name}</a></h6>
                            <p>GHS ${item.price}</p>
                        </div>
                    </div>
                    <div class="qty-item text-center">
                        <a href="javascript:void(0);" onclick="updateQuantity(${item.cart_id}, ${item.quantity - 1})" class="button-minus dec d-flex justify-content-center align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="minus"><i data-feather="minus-circle" class="feather-14"></i></a>
                        <input type="text" class="form-control text-center quantity-field" value="${item.quantity}" readonly>
                        <a href="javascript:void(0);" onclick="updateQuantity(${item.cart_id}, ${item.quantity + 1})" class=" inc button-plus d-flex justify-content-center align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="plus"><i data-feather="plus-circle" class="feather-14"></i></a>
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
        });
    }
    
    cartContainer.innerHTML = html;
    
    // Update totals
    subtotalElement.textContent = `$${cartData.subtotal.toFixed(2)}`;
    totalElement.textContent = `$${cartData.total.toFixed(2)}`;
    checkoutTotal.textContent = `$${cartData.total.toFixed(2)}`;
}

// Update your DOMContentLoaded event listener
document.addEventListener("DOMContentLoaded", function () {
  // Load categories first
  loadCategories();

  // Load initial cart
  updateCart();
});
