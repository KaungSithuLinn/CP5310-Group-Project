document.addEventListener('DOMContentLoaded', function() {
  // Login form submission
  document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const email = this.querySelector('input[name="email"]').value;
      const password = this.querySelector('input[name="password"]').value;
      loginUser(email, password);
      this.reset();
  });

  // Registration form submission
  document.getElementById('registerForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const name = this.querySelector('input[name="name"]').value;
      const email = this.querySelector('input[name="email"]').value;
      const password = this.querySelector('input[name="password"]').value;
      registerUser(name, email, password);
      this.reset();
  });

  // Review form submission
  document.getElementById('reviewForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const reviewText = this.querySelector('textarea').value;
      submitReview(reviewText);
      this.reset();
  });

  // Function to handle login
  function loginUser(email, password) {
      // Example: Send data to server using fetch or XMLHttpRequest
      console.log(`Logging in with email: ${email} and password: ${password}`);
      // Replace with actual server communication
  }

  // Function to handle registration
  function registerUser(name, email, password) {
      // Example: Send data to server using fetch or XMLHttpRequest
      console.log(`Registering user: ${name} (${email})`);
      // Replace with actual server communication
  }

  // Function to handle review submission
  function submitReview(review) {
      // Example: Send data to server using fetch or XMLHttpRequest
      console.log(`Submitting review: ${review}`);
      // Replace with actual server communication
  }
});
function showRoomDetails(roomId) {
    const roomDetails = {
        1: {
            image: 'images/room1.jpg',
            title: 'Room 1',
            description: 'A cozy room with all amenities.',
            price: '$100 per night'
        },
        2: {
            image: 'images/room2.jpg',
            title: 'Room 2',
            description: 'A luxurious room with sea view.',
            price: '$200 per night'
        },
        3: {
            image: 'images/room3.jpg',
            title: 'Room 3',
            description: 'A spacious room with modern facilities.',
            price: '$150 per night'
        }
    };

    const room = roomDetails[roomId];
    document.getElementById('roomImage').src = room.image;
    document.getElementById('roomTitle').innerText = room.title;
    document.getElementById('roomDescription').innerText = room.description;
    document.getElementById('roomPrice').innerText = room.price;

    $('#roomDetailsModal').modal('show');
}

function addToCart() {
    alert('Room added to cart!');
}

document.addEventListener('DOMContentLoaded', function () {
    const cartBadge = document.querySelector('.cart-badge');
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
  
    function updateCartBadge() {
      cartBadge.textContent = cart.length;
      if (cart.length > 0) {
        cartBadge.classList.add('active');
      } else {
        cartBadge.classList.remove('active');
      }
    }
  
    function addToCart(room) {
      cart.push(room);
      localStorage.setItem('cart', JSON.stringify(cart));
      updateCartBadge();
    }
  
    document.querySelectorAll('.add-to-cart').forEach(button => {
      button.addEventListener('click', function () {
        const room = this.getAttribute('data-room');
        addToCart(room);
        window.location.href = 'cart.html';
      });
    });
  
    updateCartBadge();
  });
  