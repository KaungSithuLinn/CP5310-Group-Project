document.addEventListener('DOMContentLoaded', function() {
  // Cart management
  const cartBadge = document.querySelector('.cart-badge');
  let cart = JSON.parse(localStorage.getItem('cart')) || [];

  function updateCartBadge() {
      cartBadge.textContent = cart.length;
      cartBadge.style.display = cart.length > 0 ? 'inline' : 'none';
  }

  function addToCart(roomId, roomName, roomPrice) {
      cart.push({ id: roomId, name: roomName, price: roomPrice, quantity: 1 });
      localStorage.setItem('cart', JSON.stringify(cart));
      updateCartBadge();
      showNotification('Room added to cart!');
  }

  function removeFromCart(index) {
      cart.splice(index, 1);
      localStorage.setItem('cart', JSON.stringify(cart));
      updateCartBadge();
      showNotification('Room removed from cart.');
      if (window.location.pathname.includes('cart.html')) {
          displayCart();
      }
  }

  function updateQuantity(index, newQuantity) {
      if (newQuantity > 0) {
          cart[index].quantity = newQuantity;
          localStorage.setItem('cart', JSON.stringify(cart));
          if (window.location.pathname.includes('cart.html')) {
              displayCart();
          }
      }
  }

  // Display cart on cart page
  function displayCart() {
      const cartContainer = document.getElementById('cart-items');
      const totalElement = document.getElementById('cart-total');
      if (cartContainer) {
          cartContainer.innerHTML = '';
          let total = 0;
          cart.forEach((item, index) => {
              const itemTotal = item.price * item.quantity;
              total += itemTotal;
              cartContainer.innerHTML += `
                  <div class="cart-item">
                      <h3>${item.name}</h3>
                      <p>Price: $${item.price}</p>
                      <p>Quantity: 
                          <button onclick="updateQuantity(${index}, ${item.quantity - 1})">-</button>
                          <span>${item.quantity}</span>
                          <button onclick="updateQuantity(${index}, ${item.quantity + 1})">+</button>
                      </p>
                      <p>Total: $${itemTotal}</p>
                      <button onclick="removeFromCart(${index})">Remove</button>
                  </div>
              `;
          });
          if (totalElement) {
              totalElement.textContent = `$${total}`;
          }
      }
  }

  // Initialize cart display
  updateCartBadge();
  if (window.location.pathname.includes('cart.html')) {
      displayCart();
  }

  // Add to cart buttons
  document.querySelectorAll('.add-to-cart').forEach(button => {
      button.addEventListener('click', function() {
          const roomId = this.dataset.roomId;
          const roomName = this.dataset.roomName;
          const roomPrice = parseFloat(this.dataset.roomPrice);
          addToCart(roomId, roomName, roomPrice);
      });
  });

  // Form submissions
  const forms = {
      'login-form': handleLogin,
      'register-form': handleRegister,
      'contact-form': handleContact,
      'newsletter-form': handleNewsletter
  };

  Object.keys(forms).forEach(formId => {
      const form = document.getElementById(formId);
      if (form) {
          form.addEventListener('submit', function(e) {
              e.preventDefault();
              forms[formId](new FormData(this));
          });
      }
  });

  function handleLogin(formData) {
      // Implement login logic here
      console.log('Login submitted', Object.fromEntries(formData));
      showNotification('Login successful!');
  }

  function handleRegister(formData) {
      // Implement registration logic here
      console.log('Registration submitted', Object.fromEntries(formData));
      showNotification('Registration successful!');
  }

  function handleContact(formData) {
      // Implement contact form submission logic here
      console.log('Contact form submitted', Object.fromEntries(formData));
      showNotification('Message sent successfully!');
  }

  function handleNewsletter(formData) {
      // Implement newsletter signup logic here
      console.log('Newsletter signup', Object.fromEntries(formData));
      showNotification('Thank you for subscribing to our newsletter!');
  }

  // Notification function
  function showNotification(message) {
      const notification = document.createElement('div');
      notification.className = 'notification';
      notification.textContent = message;
      document.body.appendChild(notification);
      setTimeout(() => {
          notification.remove();
      }, 3000);
  }

  // Room details modal
  const roomDetailsModal = document.getElementById('roomDetailsModal');
  if (roomDetailsModal) {
      roomDetailsModal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget;
          const roomId = button.getAttribute('data-room-id');
          const roomDetails = getRoomDetails(roomId); // Implement this function to fetch room details
          
          const modalTitle = roomDetailsModal.querySelector('.modal-title');
          const modalBody = roomDetailsModal.querySelector('.modal-body');
          
          modalTitle.textContent = roomDetails.name;
          modalBody.innerHTML = `
              <img src="${roomDetails.image}" alt="${roomDetails.name}" class="img-fluid mb-3">
              <p>${roomDetails.description}</p>
              <p><strong>Price:</strong> $${roomDetails.price} per night</p>
          `;
      });
  }

  // Implement this function to return room details based on roomId
  function getRoomDetails(roomId) {
      // This should be replaced with actual data, possibly fetched from a server
      const rooms = {
          1: { name: "Deluxe Room", description: "Spacious room with city view", price: 200, image: "images/deluxe-room.jpg" },
          2: { name: "Suite", description: "Luxurious suite with separate living area", price: 350, image: "images/suite.jpg" },
          3: { name: "Family Room", description: "Perfect for families, with two bedrooms", price: 300, image: "images/family-room.jpg" }
      };
      return rooms[roomId];
  }

  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
          e.preventDefault();
          document.querySelector(this.getAttribute('href')).scrollIntoView({
              behavior: 'smooth'
          });
      });
  });
});