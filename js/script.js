document.addEventListener('DOMContentLoaded', function() {
    const cartBadge = document.querySelector('.cart-badge');

    function updateCartBadge() {
        fetch('backend/cart.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const cartItemCount = data.cart.reduce((total, item) => total + item.quantity, 0);
                    cartBadge.textContent = cartItemCount;
                    cartBadge.style.display = cartItemCount > 0 ? 'inline' : 'none';
                }
            })
            .catch(error => console.error('Error updating cart badge:', error));
    }

    function addToCart(roomId, roomName, roomPrice) {
        console.log('Adding to cart:', roomId, roomName, roomPrice);
        const formData = new FormData();
        formData.append('action', 'add');
        formData.append('room_id', roomId);
        formData.append('quantity', 1);

        fetch('backend/cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Raw response:', response);
            return response.json();
        })
        .then(data => {
            console.log('Parsed response:', data);
            if (data.success) {
                showNotification('Room added to cart!');
                updateCartBadge();
            } else {
                showNotification(data.message || 'Failed to add room to cart.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        });
    }

    document.querySelectorAll('.add-to-cart').forEach(button => {
        console.log('Add to cart button found:', button);
        button.addEventListener('click', function() {
            console.log('Add to cart button clicked');
            const roomId = this.dataset.roomId;
            const roomName = this.dataset.roomName;
            const roomPrice = parseFloat(this.dataset.roomPrice);
            addToCart(roomId, roomName, roomPrice);
        });
    });

    // Form submissions
    const forms = {
        'loginForm': handleLogin,
        'registerForm': handleRegister,
        'contactForm': handleContact,
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
        fetch('backend/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                sessionStorage.setItem('isLoggedIn', 'true');
                sessionStorage.setItem('username', data.username);
                sessionStorage.setItem('welcomeMessage', 
                    data.isNewUser ? `Welcome, ${data.username}!` : `Welcome back, ${data.username}!`);
                window.location.href = 'index.html';
            } else {
                showNotification(data.message || 'Login failed. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        });
    }

    function handleRegister(formData) {
        fetch('backend/register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Registration successful! Please log in.');
                window.location.href = 'login.html';
            } else {
                showNotification(data.message || 'Registration failed. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        });
    }

    function handleContact(formData) {
        fetch('backend/contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Message sent successfully!');
                document.getElementById('contactForm').reset();
            } else {
                showNotification(data.message || 'Failed to send message. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        });
    }

    function handleNewsletter(formData) {
        // Implement newsletter signup logic here
        console.log('Newsletter signup', Object.fromEntries(formData));
        showNotification('Thank you for subscribing to our newsletter!');
    }

    // Notification function
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
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

    // Check for welcome message on page load
    const welcomeMessage = sessionStorage.getItem('welcomeMessage');
    if (welcomeMessage) {
        showNotification(welcomeMessage);
        sessionStorage.removeItem('welcomeMessage');
    }

    // Update navigation based on login status
    updateNavigation();

    function updateNavigation() {
        const isLoggedIn = sessionStorage.getItem('isLoggedIn') === 'true';
        const username = sessionStorage.getItem('username');
        const navbarNav = document.querySelector('.navbar-nav');
        const loginLink = navbarNav.querySelector('a[href="login.html"]');

        if (isLoggedIn && username && loginLink) {
            const accountItem = document.createElement('li');
            accountItem.className = 'nav-item';
            accountItem.innerHTML = `<a class="nav-link" href="account.html">Account (${username})</a>`;
            
            const logoutItem = document.createElement('li');
            logoutItem.className = 'nav-item';
            logoutItem.innerHTML = '<a class="nav-link" href="#" id="logout-link">Logout</a>';
            
            loginLink.parentNode.replaceWith(accountItem, logoutItem);

            document.getElementById('logout-link').addEventListener('click', handleLogout);
        }
    }

    function handleLogout(e) {
        e.preventDefault();
        sessionStorage.removeItem('isLoggedIn');
        sessionStorage.removeItem('username');
        showNotification('You have been logged out.');
        window.location.href = 'index.html';
    }

    updateCartBadge();
});