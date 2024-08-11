document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("loginForm");
  const loginMessage = document.getElementById("loginMessage");

  // Client-side validation (C.1, D.12)
  form.addEventListener(
    "submit",
    function (event) {
      event.preventDefault();
      if (!form.checkValidity()) {
        event.stopPropagation();
        form.classList.add("was-validated");
      } else {
        const formData = new FormData(form);
        fetch("login.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              sessionStorage.setItem("isLoggedIn", "true");
              sessionStorage.setItem("username", data.username);
              sessionStorage.setItem(
                "welcomeMessage",
                `Welcome back, ${data.username}!`
              );
              window.location.href = data.redirect;
            } else {
              showLoginMessage(
                data.message || "Login failed. Please try again.",
                "danger"
              );
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            showLoginMessage("An error occurred. Please try again.", "danger");
          });
      }
    },
    false
  );

  // Pre-fill form with valid data if available (C.5)
  const email = sessionStorage.getItem("loginEmail");
  if (email) {
    document.getElementById("email").value = email;
  }

  // Update cart badge
  updateCartBadge();
});

function showLoginMessage(message, type) {
  const loginMessage = document.getElementById("loginMessage");
  loginMessage.textContent = message;
  loginMessage.className = `mt-3 alert alert-${type}`;
  loginMessage.style.display = "block";
}
