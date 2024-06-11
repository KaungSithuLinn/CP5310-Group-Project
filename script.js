document.addEventListener("DOMContentLoaded", (event) => {
  const form = document.getElementById("contactForm");
  const formResponse = document.getElementById("formResponse");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;

    formResponse.textContent = `Thank you for contacting us, ${name}! We will respond to ${email} shortly.`;

    form.reset();
  });
});
