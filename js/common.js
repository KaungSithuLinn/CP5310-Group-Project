document.addEventListener("DOMContentLoaded", function () {
  updateNavigation();
  updateCartBadge();
  checkWelcomeMessage();
});

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

function checkWelcomeMessage() {
  const welcomeMessage = sessionStorage.getItem("welcomeMessage");
  if (welcomeMessage) {
    showNotification(welcomeMessage, "success");
    sessionStorage.removeItem("welcomeMessage");
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

function logout() {
  fetch("backend/logout.php", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        sessionStorage.removeItem("isLoggedIn");
        sessionStorage.removeItem("username");
        showNotification("You have been successfully logged out.", "success");
        setTimeout(() => {
          window.location.href = "index.php";
        }, 2000);
      } else {
        showNotification("Error logging out. Please try again.", "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("An error occurred. Please try again.", "error");
    });
}
