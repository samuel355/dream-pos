document.addEventListener("DOMContentLoaded", function () {
  loadCategories();

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
});

// Load Categories
function loadCategories() {
  fetch("php/get-categories.php")
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        displayCategories(data.categories);
      }
    })
    .catch((error) => console.error("Error:", error));
}

function displayCategories(categories) {
  const categoriesList = document.querySelector(".categories-content");

  let html = "";
  categories.forEach((category) => {
    html += `
          <tr>
            <td class="productimgname">
              <a href="javascript:void(0);" class="product-img">
                <img src="${category.image}" alt="${category.name}">
              </a>
            </td>
            <td>${category.name}</td>
            <td>${category.created_by ?? "Admin"}</td>
            <td>
              <a class="me-3" href="#">
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
      } if(data.status ==='error') {
        toastr.error(data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}
