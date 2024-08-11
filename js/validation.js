// validation.js

document.addEventListener("DOMContentLoaded", function () {
  // Login form validation
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value;

      if (email === "" || password === "") {
        showError("Email and password are required.");
      } else if (!isValidEmail(email)) {
        showError("Please enter a valid email address.");
      } else {
        submitForm(this, "backend/login.php");
      }
    });
  }

  // Registration form validation
  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const fName = document.getElementById("fName").value.trim();
      const lName = document.getElementById("lName").value.trim();
      const email = document.getElementById("email").value.trim();
      const username = document.getElementById("username").value.trim();
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirm_password").value;
      const dob = document.getElementById("date_of_birth").value;
      const gender = document.getElementById("gender").value;
      const passport = document.getElementById("passport").value.trim();
      const address = document.getElementById("address").value.trim();
      const country = document.getElementById("country").value.trim();
      const postcode = document.getElementById("postcode").value.trim();

      if (
        !validateRegistrationForm(
          fName,
          lName,
          email,
          username,
          password,
          confirmPassword,
          dob,
          gender,
          passport,
          address,
          country,
          postcode
        )
      ) {
        return;
      }

      submitForm(this, "backend/register.php");
    });
  }

  // Delete account functionality
  const deleteAccountBtn = document.getElementById("delete-account-btn");
  if (deleteAccountBtn) {
    deleteAccountBtn.addEventListener("click", function () {
      if (
        confirm(
          "Are you sure you want to delete your account? This action cannot be undone."
        )
      ) {
        fetch("backend/delete_account.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ action: "delete" }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              showSuccess(data.message);
              if (data.redirect) {
                window.location.href = data.redirect;
              } else {
                window.location.href = "index.php"; // Fallback redirect
              }
            } else {
              showError(data.message);
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            showError("An error occurred. Please try again.");
          });
      }
    });
  }
});

function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function isValidPostcode(postcode) {
  // This is a simple validation. Adjust based on your specific requirements.
  return /^\d{5,6}$/.test(postcode);
}

function validateRegistrationForm(
  fName,
  lName,
  email,
  username,
  password,
  confirmPassword,
  dob,
  gender,
  passport,
  address,
  country,
  postcode
) {
  if (
    fName === "" ||
    lName === "" ||
    email === "" ||
    username === "" ||
    password === "" ||
    confirmPassword === "" ||
    dob === "" ||
    gender === "" ||
    passport === "" ||
    address === "" ||
    country === "" ||
    postcode === ""
  ) {
    showError("All fields are required.");
    return false;
  }
  if (!isValidEmail(email)) {
    showError("Please enter a valid email address.");
    return false;
  }
  if (password !== confirmPassword) {
    showError("Passwords do not match.");
    return false;
  }
  if (password.length < 8) {
    showError("Password must be at least 8 characters long.");
    return false;
  }
  if (!isValidPostcode(postcode)) {
    showError("Please enter a valid postcode.");
    return false;
  }
  return true;
}

function submitForm(form, url) {
  const formData = new FormData(form);
  fetch(url, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert(data.message);
        if (data.redirect) {
          window.location.href = data.redirect;
        }
      } else {
        showError(data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showError("An error occurred. Please try again.");
    });
}

function showError(message) {
  const errorDiv = document.getElementById("error-message");
  if (errorDiv) {
    errorDiv.textContent = message;
    errorDiv.style.display = "block";
  } else {
    alert(message);
  }
}

function showSuccess(message) {
  const successDiv = document.getElementById("success-message");
  if (successDiv) {
    successDiv.textContent = message;
    successDiv.style.display = "block";
  } else {
    alert(message);
  }
}
