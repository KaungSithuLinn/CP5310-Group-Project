// Define updateCartBadge function outside of DOMContentLoaded
function updateCartBadge() {
  fetch("backend/cart.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const cartBadge = document.querySelector(".cart-badge");
        const cartItemCount = data.cart.reduce(
          (total, item) => total + item.quantity,
          0
        );
        cartBadge.textContent = cartItemCount;
        cartBadge.style.display = cartItemCount > 0 ? "inline" : "none";
      }
    })
    .catch((error) => console.error("Error updating cart badge:", error));
}

// Main function to run when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  initializeRoomDetailsModal();
  initializeSmoothScrolling();
  checkWelcomeMessage();
  updateNavigation();
  initializeProfileEditing();
  updateCartBadge();
  initializeAddToCartButtons();

  if (window.location.pathname.includes("account.php")) {
    fetchUserInfo();
  }

  // Function to update navigation menu
  function updateNavMenu() {
    const isLoggedIn = sessionStorage.getItem("isLoggedIn") === "true";
    const username = sessionStorage.getItem("username");
    const navbarNav = document.querySelector("#navbarNav .navbar-nav");

    if (navbarNav) {
      if (isLoggedIn && username) {
        // User is logged in
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
        // User is not logged in
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

  // Call updateNavMenu on page load
  updateNavMenu();

  // Function to update user info display
  function updateUserInfoDisplay() {
    const userInfoContainer = document.querySelector(".user-info");
    if (userInfoContainer) {
      fetch("backend/get_user_info.php")
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            const user = data.user;
            userInfoContainer.innerHTML = `
              <p><strong>First Name:</strong> ${user.fName}</p>
              <p><strong>Last Name:</strong> ${user.lName}</p>
              <p><strong>Email:</strong> ${user.email}</p>
              <p><strong>Username:</strong> ${user.username}</p>
              <p><strong>Date of Birth:</strong> ${user.date_of_birth}</p>
              <p><strong>Gender:</strong> ${user.gender}</p>
              <p><strong>Passport:</strong> ${user.passport}</p>
              <p><strong>Address:</strong> ${user.address}</p>
              <p><strong>Country:</strong> ${user.country}</p>
              <p><strong>Postcode:</strong> ${user.postcode}</p>
            `;
          } else {
            userInfoContainer.textContent =
              "Error loading user data. Please try again later.";
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          userInfoContainer.textContent =
            "An error occurred. Please try again.";
        });
    }
  }

  // Call updateUserInfoDisplay on account page
  if (window.location.pathname.includes("account.php")) {
    updateUserInfoDisplay();
  }

  // Update cart badge
  function updateCartBadge() {
    const cartBadge = document.querySelector(".cart-badge");
    if (cartBadge) {
      fetch("backend/get_cart_count.php")
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            cartBadge.textContent = data.count;
            cartBadge.style.display = data.count > 0 ? "inline" : "none";
          }
        })
        .catch((error) => console.error("Error updating cart badge:", error));
    }
  }

  // Call updateCartBadge on page load
  updateCartBadge();
});

// Initialize room details modal
function initializeRoomDetailsModal() {
  const roomDetailsModal = document.getElementById("roomDetailsModal");
  if (roomDetailsModal) {
    roomDetailsModal.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;
      const roomId = button.getAttribute("data-room-id");
      const roomDetails = getRoomDetails(roomId);

      const modalTitle = roomDetailsModal.querySelector(".modal-title");
      const modalBody = roomDetailsModal.querySelector(".modal-body");

      modalTitle.textContent = roomDetails.name;
      modalBody.innerHTML = `
        <img src="${roomDetails.image}" alt="${roomDetails.name}" class="img-fluid mb-3">
        <p>${roomDetails.description}</p>
        <p><strong>Price:</strong> $${roomDetails.price} per night</p>
      `;
    });
  }
}

// Get room details based on roomId
function getRoomDetails(roomId) {
  // This should be replaced with actual data, possibly fetched from a server
  const rooms = {
    1: {
      name: "Deluxe Room",
      description: "Spacious room with city view",
      price: 200,
      image: "images/deluxe-room.jpg",
    },
    2: {
      name: "Suite",
      description: "Luxurious suite with separate living area",
      price: 350,
      image: "images/suite.jpg",
    },
    3: {
      name: "Family Room",
      description: "Perfect for families, with two bedrooms",
      price: 300,
      image: "images/family-room.jpg",
    },
  };
  return rooms[roomId];
}

// Initialize smooth scrolling for anchor links
function initializeSmoothScrolling() {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute("href")).scrollIntoView({
        behavior: "smooth",
      });
    });
  });
}

// Check for welcome message on page load
function checkWelcomeMessage() {
  const welcomeMessage = sessionStorage.getItem("welcomeMessage");
  if (welcomeMessage) {
    showNotification(welcomeMessage);
    sessionStorage.removeItem("welcomeMessage");
  }
}

// Update navigation based on login status
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

// Handle logout
function handleLogout(e) {
  e.preventDefault();
  sessionStorage.removeItem("isLoggedIn");
  sessionStorage.removeItem("username");
  showNotification("You have been logged out.");
  window.location.href = "index.php"; // Redirect to home page after logout
}

// Initialize profile editing functionality
function initializeProfileEditing() {
  const editProfileBtn = document.getElementById("edit-profile-btn");
  if (editProfileBtn) {
    editProfileBtn.addEventListener("click", fetchUserDataForEditing);
  }

  const editProfileForm = document.getElementById("editProfileForm");
  if (editProfileForm) {
    editProfileForm.addEventListener("submit", handleProfileUpdate);
  }
}

// Fetch user data for editing
function fetchUserDataForEditing() {
  console.log("Edit profile button clicked");
  fetch("backend/get_user_info.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        populateEditForm(data.user);
        $("#editProfileModal").modal("show");
      } else {
        showNotification("Failed to fetch user data", "error");
      }
    })
    .catch((error) => {
      console.error("Error fetching user data:", error);
      showNotification("An error occurred", "error");
    });
}

// Populate edit form with user data
function populateEditForm(user) {
  document.getElementById("edit-fName").value = user.fName || "";
  document.getElementById("edit-lName").value = user.lName || "";
  document.getElementById("edit-email").value = user.email || "";
  document.getElementById("edit-username").value = user.username || "";
  document.getElementById("edit-dob").value = user.date_of_birth || "";
  document.getElementById("edit-gender").value = user.gender || "";
  document.getElementById("edit-passport").value = user.passport || "";
  document.getElementById("edit-address").value = user.address || "";
  document.getElementById("edit-country").value = user.country || "";
  document.getElementById("edit-postcode").value = user.postcode || "";
}

// Handle profile update
function handleProfileUpdate(e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch("backend/update_user_info.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showNotification("Profile updated successfully!");
        $("#editProfileModal").modal("hide");
        fetchUserInfo();
      } else {
        showNotification(data.message || "Failed to update profile.", "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("An error occurred. Please try again.", "error");
    });
}

// Show notification
function showNotification(message, type = "success") {
  const notification = document.createElement("div");
  notification.className = `notification ${type}`;
  notification.textContent = message;
  document.body.appendChild(notification);
  setTimeout(() => {
    notification.remove();
  }, 3000);
}

// Fetch user info
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

// Update user info display
function updateUserInfoDisplay(user) {
  document.getElementById("user-fname").textContent =
    user.fName || "Not available";
  document.getElementById("user-lname").textContent =
    user.lName || "Not available";
  document.getElementById("user-email").textContent =
    user.email || "Not available";
  document.getElementById("user-username").textContent =
    user.username || "Not available";
  document.getElementById("user-dob").textContent =
    user.date_of_birth || "Not available";
  document.getElementById("user-gender").textContent =
    user.gender || "Not available";
  document.getElementById("user-passport").textContent =
    user.passport || "Not available";
  document.getElementById("user-address").textContent =
    user.address || "Not available";
  document.getElementById("user-country").textContent =
    user.country || "Not available";
  document.getElementById("user-postcode").textContent =
    user.postcode || "Not available";
}

// Initialize Add to Cart buttons
function initializeAddToCartButtons() {
  const addToCartButtons = document.querySelectorAll(".add-to-cart");
  addToCartButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const roomId = this.getAttribute("data-room-id");
      const roomName = this.getAttribute("data-room-name");
      const roomPrice = this.getAttribute("data-room-price");
      addToCart(roomId, roomName, roomPrice);
    });
  });
}

function addToCart(roomId, roomName, roomPrice) {
  fetch("backend/cart.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `action=add&room_id=${roomId}&name=${roomName}&price=${roomPrice}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showNotification("Room added to cart successfully");
        updateCartBadge();
      } else {
        showNotification("Failed to add room to cart", "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("An error occurred. Please try again.", "error");
    });
}
