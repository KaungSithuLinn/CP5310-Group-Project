document.addEventListener("DOMContentLoaded", function () {
  const editProfileBtn = document.getElementById("edit-profile-btn");
  const editProfileForm = document.getElementById("editProfileForm");
  const deleteAccountBtn = document.getElementById("delete-account-btn");

  // Fetch and display user info on page load
  fetchUserInfo();

  editProfileBtn.addEventListener("click", function () {
    $("#editProfileModal").modal("show");
    populateEditForm();
  });

  editProfileForm.addEventListener("submit", function (e) {
    e.preventDefault();
    if (!this.checkValidity()) {
      e.stopPropagation();
      this.classList.add("was-validated");
      return;
    }
    updateUserProfile(this);
  });

  deleteAccountBtn.addEventListener("click", function () {
    if (
      confirm(
        "Are you sure you want to delete your account? This action cannot be undone."
      )
    ) {
      deleteAccount();
    }
  });

  // Update cart badge
  updateCartBadge();
});

function fetchUserInfo() {
  fetch("backend/get_user_info.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        updateUserInfoDisplay(data.user);
      } else {
        showNotification(
          "Failed to fetch user info. Please try again.",
          "error"
        );
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("An error occurred. Please try again.", "error");
    });
}

function updateUserInfoDisplay(user) {
  const userInfoElements = {
    fname: user.fName,
    lname: user.lName,
    email: user.email,
    username: user.username,
    date_of_birth: user.date_of_birth,
    gender: user.gender,
    passport: user.passport,
    address: user.address,
    country: user.country,
    postcode: user.postcode,
  };

  for (const [key, value] of Object.entries(userInfoElements)) {
    const el = document.getElementById(`user-${key}`);
    if (el) {
      el.textContent = value || "Not available";
    }
  }
}

function populateEditForm() {
  const userInfoElements = [
    "fName",
    "lName",
    "email",
    "username",
    "date_of_birth",
    "gender",
    "passport",
    "address",
    "country",
    "postcode",
  ];

  userInfoElements.forEach((element) => {
    const input = document.getElementById(`edit-${element.toLowerCase()}`);
    const display = document.getElementById(`user-${element.toLowerCase()}`);
    if (input && display) {
      input.value =
        display.textContent !== "Not available" ? display.textContent : "";
    }
  });
}

function updateUserProfile(form) {
  const formData = new FormData(form);
  fetch("backend/update_user_info.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showNotification("Profile updated successfully", "success");
        $("#editProfileModal").modal("hide");
        fetchUserInfo();
      } else {
        showNotification("Error updating profile: " + data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("An error occurred. Please try again.", "error");
    });
}

function deleteAccount() {
  fetch("backend/delete_account.php", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showNotification(data.message, "success");
        // Clear session storage
        sessionStorage.removeItem("isLoggedIn");
        sessionStorage.removeItem("username");
        // Update navigation
        updateNavigation();
        // Redirect to home page after a delay
        setTimeout(() => {
          window.location.href = "index.php";
        }, 3000);
      } else {
        showNotification("Error deleting account: " + data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("An error occurred. Please try again.", "error");
    });
}

function updateNavigation() {
  const isLoggedIn = sessionStorage.getItem("isLoggedIn") === "true";
  const username = sessionStorage.getItem("username");
  const navbarNav = document.querySelector("#navbarNav .navbar-nav");

  if (navbarNav) {
    if (isLoggedIn && username) {
      navbarNav.innerHTML = `
              <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="accommodations.php">Accommodations</a></li>
              <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
              <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      ${username}
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="account.php">My Account</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="backend/logout.php">Logout</a>
                  </div>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="cart-page.php" aria-label="Shopping Cart">
                      <i class="fas fa-shopping-cart"></i>
                      <span class="cart-badge">0</span>
                  </a>
              </li>
          `;
    } else {
      navbarNav.innerHTML = `
              <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="accommodations.php">Accommodations</a></li>
              <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
              <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
              <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
              <li class="nav-item">
                  <a class="nav-link" href="cart-page.php" aria-label="Shopping Cart">
                      <i class="fas fa-shopping-cart"></i>
                      <span class="cart-badge">0</span>
                  </a>
              </li>
          `;
    }
  }
}

function showNotification(message, type) {
  const notificationDiv = document.createElement("div");
  notificationDiv.className = `alert alert-${type} alert-dismissible fade show`;
  notificationDiv.role = "alert";
  notificationDiv.style.position = "fixed";
  notificationDiv.style.top = "20px";
  notificationDiv.style.left = "50%";
  notificationDiv.style.transform = "translateX(-50%)";
  notificationDiv.style.zIndex = "9999";
  notificationDiv.innerHTML = `
    ${message}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  `;
  document.body.appendChild(notificationDiv);
  setTimeout(() => {
    notificationDiv.remove();
  }, 5000);
}

function updateCartBadge() {
  fetch("backend/get_cart_count.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const cartBadge = document.querySelector(".cart-badge");
        if (cartBadge) {
          cartBadge.textContent = data.count;
          cartBadge.style.display = data.count > 0 ? "inline" : "none";
        }
      }
    })
    .catch((error) => console.error("Error updating cart badge:", error));
}
