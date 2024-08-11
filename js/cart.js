document.addEventListener("DOMContentLoaded", function () {
  const cartItemsContainer = document.getElementById("cart-items");
  const totalPriceElement = document.getElementById("total-price");
  const checkoutBtn = document.getElementById("checkout-btn");
  const emptyCartMessage = document.getElementById("empty-cart-message");
  const clearCartBtn = document.getElementById("clear-cart-btn");

  function fetchCartItems() {
    fetch("backend/cart.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          renderCartItems(data.cart);
          updateCartBadge(data.cart);
        } else {
          showNotification(
            "Failed to fetch cart items: " + data.message,
            "error"
          );
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showNotification(
          "An error occurred while fetching cart items",
          "error"
        );
      });
  }

  function renderCartItems(cartItems) {
    if (
      !cartItemsContainer ||
      !totalPriceElement ||
      !checkoutBtn ||
      !emptyCartMessage
    ) {
      console.error("One or more required elements are missing from the DOM");
      return;
    }

    cartItemsContainer.innerHTML = "";
    let totalPrice = 0;

    if (cartItems.length === 0) {
      emptyCartMessage.style.display = "block";
      checkoutBtn.style.display = "none";
      cartItemsContainer.style.display = "none";
    } else {
      emptyCartMessage.style.display = "none";
      checkoutBtn.style.display = "block";
      cartItemsContainer.style.display = "block";

      cartItems.forEach((item) => {
        const cartItem = document.createElement("div");
        cartItem.className = "card mb-3";
        cartItem.innerHTML = `
                  <div class="row no-gutters">
                      <div class="col-md-4">
                          <img src="${item.image}" class="card-img" alt="${
          item.name
        }">
                      </div>
                      <div class="col-md-8">
                          <div class="card-body">
                              <h5 class="card-title">${item.name}</h5>
                              <p class="card-text">${item.description}</p>
                              <p class="card-text"><strong>Price:</strong> $${parseFloat(
                                item.price
                              ).toFixed(2)} per night</p>
                              <div class="d-flex justify-content-between align-items-center">
                                  <div class="input-group" style="width: 120px;">
                                      <div class="input-group-prepend">
                                          <button class="btn btn-outline-secondary decrease-quantity" type="button" data-room-id="${
                                            item.room_id
                                          }">-</button>
                                      </div>
                                      <input type="text" class="form-control text-center item-quantity" value="${
                                        item.quantity
                                      }" readonly>
                                      <div class="input-group-append">
                                          <button class="btn btn-outline-secondary increase-quantity" type="button" data-room-id="${
                                            item.room_id
                                          }">+</button>
                                      </div>
                                  </div>
                                  <button class="btn btn-danger btn-sm remove-item" data-room-id="${
                                    item.room_id
                                  }">Remove</button>
                              </div>
                          </div>
                      </div>
                  </div>
              `;
        cartItemsContainer.appendChild(cartItem);
        totalPrice += item.price * item.quantity;
      });
    }

    totalPriceElement.textContent = totalPrice.toFixed(2);
    checkoutBtn.disabled = cartItems.length === 0;
  }

  function updateQuantity(roomId, quantity) {
    fetch("backend/cart.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `action=update&room_id=${roomId}&quantity=${quantity}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          fetchCartItems();
          showNotification("Cart updated successfully", "success");
        } else {
          showNotification(
            "Failed to update quantity: " + data.message,
            "error"
          );
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showNotification("An error occurred while updating the cart", "error");
      });
  }

  function removeItem(roomId) {
    fetch("backend/cart.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `action=remove&room_id=${roomId}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          fetchCartItems();
          showNotification("Item removed from cart", "success");
        } else {
          showNotification("Failed to remove item: " + data.message, "error");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showNotification("An error occurred while removing the item", "error");
      });
  }

  // Clear all items from the cart
  clearCartBtn.addEventListener("click", function () {
    fetch("backend/cart.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `action=clear`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          fetchCartItems();
          showNotification("Cart cleared successfully", "success");
        } else {
          showNotification("Failed to clear cart: " + data.message, "error");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showNotification("An error occurred while clearing the cart", "error");
      });
  });

  // Event delegation for cart item interactions
  cartItemsContainer.addEventListener("click", function (e) {
    if (e.target.classList.contains("increase-quantity")) {
      const roomId = e.target.getAttribute("data-room-id");
      const quantityInput = e.target.previousElementSibling;
      let quantity = parseInt(quantityInput.value);
      quantity++;
      quantityInput.value = quantity;
      updateQuantity(roomId, quantity);
    } else if (e.target.classList.contains("decrease-quantity")) {
      const roomId = e.target.getAttribute("data-room-id");
      const quantityInput = e.target.nextElementSibling;
      let quantity = parseInt(quantityInput.value);
      if (quantity > 1) {
        quantity--;
        quantityInput.value = quantity;
        updateQuantity(roomId, quantity);
      }
    } else if (e.target.classList.contains("remove-item")) {
      const roomId = e.target.getAttribute("data-room-id");
      removeItem(roomId);
    }
  });

  checkoutBtn.addEventListener("click", function () {
    if (checkoutBtn.disabled) {
      showNotification(
        "Your cart is empty. Add items before checking out.",
        "warning"
      );
      return;
    }

    fetch("backend/create_booking.php", {
      method: "POST",
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showNotification("Booking created successfully!", "success");
          setTimeout(() => {
            window.location.href =
              "booking_confirmation.php?id=" + data.bookingId;
          }, 2000);
        } else {
          showNotification(data.message, "error");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showNotification("An error occurred. Please try again.", "error");
      });
  });

  function showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `alert alert-${type} alert-dismissible fade show`;
    notification.role = "alert";
    notification.innerHTML = `
          ${message}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      `;
    document.body.insertBefore(notification, document.body.firstChild);
    setTimeout(() => {
      notification.remove();
    }, 5000);
  }

  function updateCartBadge(cartItems) {
    const cartBadge = document.querySelector(".cart-badge");
    if (cartBadge) {
      const itemCount = cartItems.reduce(
        (total, item) => total + item.quantity,
        0
      );
      cartBadge.textContent = itemCount;
      cartBadge.style.display = itemCount > 0 ? "inline" : "none";
    }
  }

  // Initial cart fetch
  fetchCartItems();
});
