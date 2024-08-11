document.addEventListener("DOMContentLoaded", function () {
  const addToCartButtons = document.querySelectorAll(".add-to-cart");
  addToCartButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const roomId = this.getAttribute("data-room-id");
      const roomName = this.getAttribute("data-room-name");
      const roomPrice = parseFloat(this.getAttribute("data-room-price"));

      addToCart({
        id: roomId,
        name: roomName,
        price: roomPrice,
      });
    });
  });

  // Update cart badge on page load
  updateCartBadge();
});

function addToCart(room) {
  fetch("backend/add_to_cart.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(room),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showNotification("Room added to cart!", "success");
        updateCartBadge();
      } else {
        showNotification(data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("An error occurred. Please try again.", "error");
    });
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

function showNotification(message, type) {
  const notificationDiv = document.createElement("div");
  notificationDiv.className = `alert alert-${type} alert-dismissible fade show`;
  notificationDiv.role = "alert";
  notificationDiv.innerHTML = `
    ${message}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  `;
  document.body.insertBefore(notificationDiv, document.body.firstChild);
  setTimeout(() => {
    notificationDiv.remove();
  }, 5000);
}
