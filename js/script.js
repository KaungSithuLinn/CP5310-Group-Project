document.addEventListener('DOMContentLoaded', function() {
    // Cart management
    const cartBadge = document.querySelector('.cart-badge');
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    function updateCartBadge() {
        cartBadge.textContent = cart.length;
        cartBadge.style.display = cart.length > 0 ? 'inline' : 'none';
    }

    function addToCart(roomId, roomName, roomPrice) {
        const formData = new FormData();
        formData.append('room_id', roomId);
        formData.append('quantity', 1);

        fetch('cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            showNotification('Room added to cart!');
            updateCartBadge();
        })
        .catch(error => console.error('Error:', error));
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
        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            showNotification('Login successful!');
        })
        .catch(error => console.error('Error:', error));
    }

    function handleRegister(formData) {
        fetch('register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            showNotification('Registration successful!');
        })
        .catch(error => console.error('Error:', error));
    }

    function handleContact(formData) {
        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            showNotification('Message sent successfully!');
        })
        .catch(error => console.error('Error:', error));
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