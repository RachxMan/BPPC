document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm");
  const registerForm = document.getElementById("registerForm");
  const roleButtons = document.querySelectorAll(".role-btn");
  const phoneInput = document.getElementById("phone");

  // Toggle ke form register
  document.getElementById("showRegisterBtn").addEventListener("click", () => {
    loginForm.classList.remove("active");
    registerForm.classList.add("active");
  });

  // Toggle ke form login
  const showLoginBtn = document.getElementById("showLoginBtn");
  if (showLoginBtn) {
    showLoginBtn.addEventListener("click", () => {
      registerForm.classList.remove("active");
      loginForm.classList.add("active");
    });
  }

  // Role button
  roleButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      roleButtons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
    });
  });

  // Login form submit
  loginForm.addEventListener("submit", (e) => {
    // Biarkan Laravel handle login
    // Jika mau, bisa tambahkan front-end validation disini
  });

  // Register form submit
  registerForm.addEventListener("submit", (e) => {
    // Validasi front-end
    let isValid = true;
    const password = document.getElementById("reg-password").value.trim();
    const confirmPassword = document.getElementById("confirm-password").value.trim();
    const phone = phoneInput.value.trim();

    document.querySelectorAll(".error-message").forEach(el => el.textContent = "");

    if (phone.length < 9) {
      const err = document.getElementById("phone-error");
      err.textContent = "Nomor HP minimal 9 digit!";
      isValid = false;
    }

    const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    if (!passwordRegex.test(password)) {
      const err = document.getElementById("password-error");
      err.textContent = "Password minimal 8 karakter, harus ada huruf besar, angka, dan simbol!";
      isValid = false;
    }

    if (password !== confirmPassword) {
      const err = document.getElementById("confirm-error");
      err.textContent = "Password dan Confirm Password tidak sama!";
      isValid = false;
    }

    // Jika valid, biarkan form submit ke Laravel
    if (!isValid) {
      e.preventDefault();
    }
  });

  // Format phone input
  if (phoneInput) {
    phoneInput.addEventListener("input", function () {
      this.value = this.value.replace(/[^0-9]/g, "");
      if (this.value.startsWith("0")) this.value = this.value.substring(1);
    });
  }

  // Toggle password visibility
  document.querySelectorAll(".toggle-password").forEach(btn => {
    btn.addEventListener("click", () => {
      const targetId = btn.dataset.target;
      const input = document.getElementById(targetId);
      if (!input) return;
      const img = btn.querySelector("img");
      if (input.type === "password") {
        input.type = "text";
        img.src = "/img/eye-open-svgrepo-com.svg";
      } else {
        input.type = "password";
        img.src = "/img/eye-close-svgrepo-com.svg";
      }
    });
  });
});
