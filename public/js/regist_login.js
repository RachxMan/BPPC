document.addEventListener("DOMContentLoaded", () => {
  // Toggle password visibility
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

  // Format phone input (hanya jika ada)
  const phoneInput = document.getElementById("phone");
  if (phoneInput) {
    phoneInput.addEventListener("input", function () {
      this.value = this.value.replace(/[^0-9]/g, "");
      if (this.value.startsWith("0")) this.value = this.value.substring(1);
    });
  }
});
