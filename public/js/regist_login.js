document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".toggle-password").forEach(btn => {
    btn.addEventListener("click", () => {
      const targetId = btn.dataset.target;
      const input = document.getElementById(targetId);
      if (!input) return;

      const img = btn.querySelector("img");
      if (!img) return;

      if (input.type === "password") {
        input.type = "text";
        img.src = "/img/eye-open-svgrepo-com.svg"; // sesuaikan path
      } else {
        input.type = "password";
        img.src = "/img/eye-close-svgrepo-com.svg"; // sesuaikan path
      }
    });
  });

  const phoneInput = document.getElementById("phone");
  if (phoneInput) {
    phoneInput.addEventListener("input", function () {
      this.value = this.value.replace(/[^0-9]/g, "");
      if (this.value.startsWith("0")) this.value = this.value.substring(1);
    });
  }

  const roleButtons = document.querySelectorAll('.role-btn');
  const selectedRoleInput = document.getElementById('selectedRole');
  if (roleButtons.length > 0 && selectedRoleInput) {
    roleButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        roleButtons.forEach(b => b.classList.remove('active'));

        btn.classList.add('active');

        selectedRoleInput.value = btn.dataset.role;
      });
    });
  }


  const showRegisterBtn = document.getElementById('showRegisterBtn');
  if (showRegisterBtn) {
    showRegisterBtn.addEventListener('click', () => {
      window.location.href = '/register';
    });
  }

  const showLoginBtn = document.getElementById('showLoginBtn');
  if (showLoginBtn) {
    showLoginBtn.addEventListener('click', () => {
      window.location.href = '/login';
    });
  }
});
