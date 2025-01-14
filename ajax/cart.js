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
                              style="width: 85%; height: 15vh; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); object-fit: cover; margin-bottom:6px"/>
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
                const cartData = data.data;
                
                // Get container elements
                const leftContainer = document.querySelector('.product-right-block');
                const rightContainer = document.querySelector('.order-right-block');
                const valuesContainer = document.querySelector('.setvalue')
                const orderBtn = document.querySelector('.order-btn-container')
                
                // Adjust layout based on cart items
                if (cartData.items.length === 0) {
                    if(valuesContainer){
                        valuesContainer.style.display = 'none'
                    }
                    if(orderBtn){
                        orderBtn.style.display = 'none'
                    }
                    if (leftContainer) {
                        leftContainer.classList.remove('col-lg-8');
                        leftContainer.classList.add('col-lg-12');
                    }
                    if (rightContainer) {
                        rightContainer.style.display = 'none';
                    }
                } else {
                    if(valuesContainer){
                        valuesContainer.style.display = 'block'
                    }
                    if(orderBtn){
                        orderBtn.style.display = 'block'
                    }
                    if (leftContainer) {
                        leftContainer.classList.remove('col-lg-12');
                        leftContainer.classList.add('col-lg-8');
                    }
                    if (rightContainer) {
                        rightContainer.style.display = 'block';
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
    const cartContainer = document.querySelector('.product-wrap');
    const totalItems = document.querySelector('.count-items');
    const subtotalElement = document.querySelector('.cart-subtotal');
    const totalElement = document.querySelector('.cart-total-items');
    const checkoutTotal = document.querySelector('.cart-total-amount');
    const checkoutCheckout = document.querySelector('.cart-total-checkout');

    // Update total items
    totalItems.textContent = `  ${cartData.items.length}`;
    
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

                    <div class="increment-decrement">
                        <div class="input-groups">
                            <input onclick="updateQuantity(${item.cart_id}, ${item.quantity - 1})" type="button" value="-"
                                class="button-minus dec button">
                            <input type="text" name="child" value="${item.quantity}" readonly
                                class="quantity-field">
                            <input onclick="updateQuantity(${item.cart_id}, ${item.quantity + 1})" type="button" value="+"
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
        });
    }
    
    cartContainer.innerHTML = html;
    
    // Update totals
    subtotalElement.textContent = ` GHS. ${cartData.subtotal.toFixed(2)}`;
    totalElement.textContent = `${cartData.total_items}`;
    checkoutTotal.textContent = ` GHS ${cartData.total.toFixed(2)}`;
    checkoutCheckout.textContent = ` GHS ${cartData.total.toFixed(2)}`;

    const createOrderBtn = document.querySelector('.order-btn-container'); 
    createOrderBtn.onclick = () => {
        previewReceipt(cartData);
    };
}

// Function to delete cart item
function deleteCartItem(cartId) {
    if (confirm('Are you sure you want to remove this item?')) {
        const formData = new FormData();
        formData.append('cart_id', cartId);
        
        fetch('php/delete-cart-item.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateCart(); // Refresh cart display
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Function to update quantity
function updateQuantity(cartId, newQuantity) {
    if (newQuantity < 1) {
        toastr.error('Quantity cannot be less than 1')
        return;
    }
    
    const formData = new FormData();
    formData.append('cart_id', cartId);
    formData.append('quantity', newQuantity);
    
    fetch('php/update-cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            updateCart(); // Refresh cart display
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to clear cart
function clearCart() {
    if (confirm('Are you sure you want to clear your cart?')) {
        fetch('php/clear-cart.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateCart(); // Refresh cart display
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function previewReceipt(cartData) {
    const receiptWindow = window.open('', '_blank', 'width=400,height=600');
    
    let html = `
        <html>
        <head>
            <title>Receipt Preview</title>
            <style>
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
                .items {
                    margin: 20px 0;
                }
                .item {
                    margin: 5px 0;
                }
                .totals {
                    margin-top: 20px;
                    text-align: right;
                }
                .footer {
                    text-align: center;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>Your Store Name</h2>
                <p>123 Store Address</p>
                <p>City, State, ZIP</p>
                <p>Tel: (123) 456-7890</p>
                <p>Date: ${new Date().toLocaleString()}</p>
                <p>Receipt #: ${Date.now()}</p>
            </div>
            
            <div class="items">
    `;
    
    cartData.items.forEach(item => {
        html += `
            <div class="item">
                <div>${item.name}</div>
                <div style="margin-left:8px">${item.quantity} x 
                ${item.price} = ${(item.quantity * item.price).toFixed(2)}</div>
                <hr />
            </div>
        `;
    });
    
    html += `
            </div>
            
            <div class="totals">
                <p>Subtotal: GHS ${cartData.subtotal.toFixed(2)}</p>
                <p>Total: GHS ${cartData.total.toFixed(2)}</p>
            </div>
            
            <div class="footer">
                <p>Thank you for your purchase!</p>
                <p>Please come again</p>
            </div>
        </body>
        </html>
    `;
    
    receiptWindow.document.write(html);
}

// Update your DOMContentLoaded event listener
document.addEventListener("DOMContentLoaded", function () {
  // Load categories first
  loadCategories();

  // Load initial cart
  updateCart();
});
