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
              <a href="javascript:void(0);"> <img src="${
                category.image
              }" alt="${category.name}"> </a>
              <h6>${category.name}</h6>
              <span>4 Items</span>
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

      // Get category ID and load products
      const categoryId = this.dataset.categoryId;

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

  // Fetch products
  fetch(`php/get-products.php?category_id=${categoryId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        displayProducts(data.data, categoryId);
      } else {
        console.error("Error loading products:", data.message);
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

  if (products.length === 0) {
    html += `
          <div class="col-12 text-center">
              <p>No products found in this category.</p>
          </div>
      `;
  } else {
    products.forEach((product) => {
      html += `
      <div class="col-sm-2 col-md-6 col-lg-3 col-xl-3">
            <div class="product-info default-cover card" onclick="addToCart(${product.id})">
                <a href="javascript:void(0);" class="img-bg">
                    <img src="../php/${product.image}"  alt="Beef Sauce" style="width: 85%; height: auto; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); object-fit: cover; margin-bottom:6px"/>
                    <span><i data-feather="check" class="feather-16"></i></span>
                </a>
                <h6 class="cat-name"><a href="javascript:void(0);">${product.category_name}</a></h6>
                <h6 class="product-name"><a href="javascript:void(0);">${product.name}</a></h6>
                <div class="d-flex align-items-center justify-content-between price">
                    <span>30 Pcs</span>
                    <p>GHS ${product.price}</p>
                </div>
            </div>
        </div>
      `;
    });
  }

  html += "</div></div>";
  productsContainer.innerHTML = html;
}

// Update your DOMContentLoaded event listener
document.addEventListener("DOMContentLoaded", function () {
  // Load categories first
  loadCategories();

  // Load initial cart
  //updateCart();
});
