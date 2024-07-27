document.addEventListener('DOMContentLoaded', function() {
    const cartItemsContainer = document.getElementById('cart-items');
    const totalPriceElement = document.getElementById('total-price');
    const checkoutBtn = document.getElementById('checkout-btn');
    const emptyCartMessage = document.getElementById('empty-cart-message');

    function fetchCartItems() {
        fetch('backend/cart.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderCartItems(data.cart);
                } else {
                    console.error('Failed to fetch cart items:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function renderCartItems(cartItems) {
        cartItemsContainer.innerHTML = '';
        let totalPrice = 0;

        if (cartItems.length === 0) {
            emptyCartMessage.style.display = 'block';
            checkoutBtn.style.display = 'none';
        } else {
            emptyCartMessage.style.display = 'none';
            checkoutBtn.style.display = 'block';

            cartItems.forEach(item => {
                const cartItem = document.createElement('div');
                cartItem.className = 'card mb-3';
                cartItem.innerHTML = `
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="${item.image}" class="card-img" alt="${item.name}">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">${item.name}</h5>
                                <p class="card-text">${item.description}</p>
                                <p class="card-text"><strong>Price:</strong> $${item.price} per night</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="input-group" style="width: 120px;">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary decrease-quantity" type="button" data-room-id="${item.id}">-</button>
                                        </div>
                                        <input type="text" class="form-control text-center item-quantity" value="${item.quantity}" readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary increase-quantity" type="button" data-room-id="${item.id}">+</button>
                                        </div>
                                    </div>
                                    <button class="btn btn-danger btn-sm remove-item" data-room-id="${item.id}">Remove</button>
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
    }

    function updateQuantity(roomId, change) {
        fetch('backend/cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=update&room_id=${roomId}&quantity_change=${change}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchCartItems();
            } else {
                console.error('Failed to update quantity:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function removeItem(roomId) {
        fetch('backend/cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=remove&room_id=${roomId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchCartItems();
            } else {
                console.error('Failed to remove item:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    cartItemsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('increase-quantity')) {
            const roomId = e.target.getAttribute('data-room-id');
            updateQuantity(roomId, 1);
        } else if (e.target.classList.contains('decrease-quantity')) {
            const roomId = e.target.getAttribute('data-room-id');
            updateQuantity(roomId, -1);
        } else if (e.target.classList.contains('remove-item')) {
            const roomId = e.target.getAttribute('data-room-id');
            removeItem(roomId);
        }
    });

    checkoutBtn.addEventListener('click', function() {
        // Implement checkout logic here
        alert('Proceeding to checkout. This feature is not yet implemented.');
    });

    // Initial cart fetch
    fetchCartItems();
});