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
