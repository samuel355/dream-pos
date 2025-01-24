document.addEventListener("DOMContentLoaded", function () {
  initializeUsersDataTable();
  loadUsers();

  // Initialize form submission for adding users
  const search_add_user = document.getElementById("add-user-form");
  if(search_add_user){
    search_add_user.addEventListener("submit", addUser);
  }

  // Initialize preview image function
  function previewImage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        $("#preview").attr("src", e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  if (typeof feather !== "undefined") {
    feather.replace();
  }
});

function initializeUsersDataTable() {
  usersTable = $("#usersTable").DataTable({
    processing: true,
    responsive: true,
    dom: "Bfrtip",
    buttons: [
      {
        extend: "collection",
        text: "Export",
        buttons: ["copy", "excel", "csv", "pdf", "print"],
      },
    ],
    columns: [
      {
        data: null,
        orderable: false,
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },
      {
        data: null,
        orderable: false,
        render: function (data, type, row, meta) {
          const imageSrc = data.image !== null ? `php/${data.image}` : 'assets/img/boba/boba-c.png'
          return `<img src="${imageSrc}" alt="${
            data.fullname
          }" class="img-fluid rounded-circle" width="30">`;
        },
      },
      { data: "fullname" },
      { data: "email" },
      { data: "username" },
      { data: "role" },
      { data: "status" },
      {
        data: null,
        render: function (data) {
          return `
                      <div class="btn-group">
                          <button class="btn btn-sm btn-warning" onclick="editUser(${data.id})">
                              <i data-feather="edit-3"></i>
                          </button>
                          <button class="btn btn-sm btn-danger" onclick="deleteUser(${data.id})">
                              <i data-feather="trash-2"></i>
                          </button>
                      </div>
                  `;
        },
      },
    ],
    drawCallback: function () {
      feather.replace();
    },
  });
}

function loadUsers() {
  fetch("php/get-users.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        usersTable.clear().rows.add(data.users).draw();
      } else {
        toastr.error(data.message || "Error loading users");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("Error loading users");
    });
}

function addUser(event) {
  event.preventDefault();
  const formData = new FormData(event.target);

  fetch("php/add-user.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        toastr.success(data.message || "User added successfully");
        loadUsers();
        $("#create-user-modal").modal("hide");
      } else {
        toastr.error(data.message || "Error adding user");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("Error adding user");
    });
}

function editUser(userId) {
  fetch(`php/get-user.php?id=${userId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        showEditUserModal(data.user);
      } else {
        toastr.error(data.message || "Error loading user data");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("Error loading user data");
    });
}

function showEditUserModal(user) {
  const modalHtml = `
      <div class="modal fade" id="edit-user-modal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Edit User</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <form id="edit-user-form" enctype="multipart/form-data">
                          <input type="hidden" name="user_id" value="${
                            user.id
                          }">
                          <div class="row">
                              <div class="col-lg-6">
                                  <div class="form-group">
                                      <label>Full Name<span class="text-danger">*</span></label>
                                      <input type="text" name="fullname" class="form-control" value="${
                                        user.fullname
                                      }" required>
                                  </div>
                              </div>
                              <div class="col-lg-6">
                                  <div class="form-group">
                                      <label>Email<span class="text-danger">*</span></label>
                                      <input type="email" name="email" class="form-control" value="${
                                        user.email
                                      }" readonly required>
                                  </div>
                              </div>
                              <div class="col-lg-6">
                                  <div class="form-group">
                                      <label>Phone<span class="text-danger">*</span></label>
                                      <input type="text" name="phone" class="form-control" value="${
                                        user.phone
                                      }" required>
                                  </div>
                              </div>
                              <div class="col-lg-6">
                                  <div class="form-group">
                                      <label>Role<span class="text-danger">*</span></label>
                                      <select name="role" class="select" required>
                                          <option value="admin" ${
                                            user.role === "admin"
                                              ? "selected"
                                              : ""
                                          }>Admin</option>
                                          <option value="cashier" ${
                                            user.role === "cashier"
                                              ? "selected"
                                              : ""
                                          }>Cashier</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="col-lg-6">
                                  <div class="form-group">
                                      <label>Password (Leave blank to keep current)</label>
                                      <input type="password" name="password" class="form-control">
                                  </div>
                              </div>
                              <div class="col-lg-6">
                                  <div class="form-group">
                                      <label>Confirm Password</label>
                                      <input type="password" name="repassword" class="form-control">
                                  </div>
                              </div>
                              <div class="col-lg-12">
                                  <div class="form-group">
                                      <label>Profile Image</label>
                                      <div class="image-upload">
                                          <input type="file" name="image" accept="image/*" onchange="previewImage(this);">
                                          <div class="image-uploads">
                                              <img src="assets/img/icons/upload.svg" alt="upload">
                                              <h4>Drag and drop a file to upload</h4>
                                              <img id="edit-preview" src="${
                                                user.image ||
                                                "assets/img/profiles/avatar-default.jpg"
                                              }" style="width: 100px; height:100px; border-radius:50px; object-fit: contain; position: absolute; top:0; right:0;">
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="submit-section">
                              <button type="submit" class="btn btn-primary submit-btn">Update User</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  `;

  // Remove existing modal if any
  const existingModal = document.getElementById("edit-user-modal");
  if (existingModal) {
    existingModal.remove();
  }

  document.body.insertAdjacentHTML("beforeend", modalHtml);

  // Add event listener for form submission
  document
    .getElementById("edit-user-form")
    .addEventListener("submit", editUserSubmit);

  // Show modal
  const modal = new bootstrap.Modal(document.getElementById("edit-user-modal"));
  modal.show();
}

function editUserSubmit(event) {
  event.preventDefault();
  const formData = new FormData(event.target);

  fetch("php/edit-user.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        toastr.success(data.message || "User updated successfully");
        loadUsers();
        $("#edit-user-modal").modal("hide");
      } else {
        toastr.error(data.message || "Error updating user");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error("Error updating user");
    });
}

function deleteUser(userId) {
  if (confirm("Are you sure you want to delete this user?")) {
    fetch("php/delete-user.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ user_id: userId }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          toastr.success(data.message);
          loadUsers();
        } else {
          toastr.error(data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        toastr.error("Error deleting user");
      });
  }
}
