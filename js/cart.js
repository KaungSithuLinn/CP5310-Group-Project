// cart.js

document.addEventListener('DOMContentLoaded', function () {
    const cartItemsContainer = document.getElementById('cart-items');
    const totalPriceElement = document.getElementById('total-price');
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
  
    function calculateTotalPrice() {
      let totalPrice = 0;
      cart.forEach(item => {
        // Assuming each item is 100 SGD for demonstration purposes
        totalPrice += 100;
      });
      return totalPrice;
    }
  
    function renderCartItems() {
      cartItemsContainer.innerHTML = '';
      cart.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.className = 'card mb-3';
        cartItem.innerHTML = `
          <div class="row no-gutters">
            <div class="col-md-4">
              <img src="img/room1.jpg" class="card-img" alt="${item}">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title">${item}</h5>
                <p class="card-text">Description for ${item}</p>
                <p class="card-text"><small class="text-muted">100 SGD</small></p>
              </div>
            </div>
          </div>
        `;
        cartItemsContainer.appendChild(cartItem);
      });
  
      totalPriceElement.textContent = calculateTotalPrice();
    }
  
    renderCartItems();
  });
  