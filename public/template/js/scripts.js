// Login page passowrd hide , unhide option
// Toggle password visibility functionality
document
  .getElementById("togglePassword")
  .addEventListener("click", function () {
    const passwordField = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    // Check the current type of the password field
    if (passwordField.type === "password") {
      passwordField.type = "text"; // Show password
      eyeIcon.classList.remove("fa-eye"); // Remove 'eye' icon
      eyeIcon.classList.add("fa-eye-slash"); // Add 'eye-slash' icon
    } else {
      passwordField.type = "password"; // Hide password
      eyeIcon.classList.remove("fa-eye-slash"); // Remove 'eye-slash' icon
      eyeIcon.classList.add("fa-eye"); // Add 'eye' icon
    }
  });
