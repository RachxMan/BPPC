document.addEventListener("DOMContentLoaded", () => {
  const fileInput = document.getElementById("fileInput");
  const fileName = document.getElementById("fileName");
  const uploadBox = document.getElementById("uploadBox");
  const uploadContent = document.getElementById("uploadContent");
  const uploadResult = document.getElementById("uploadResult");
  const selectedFileName = document.getElementById("selectedFileName");
  const btnUpload = document.getElementById("btnUpload");
  const uploadLoading = document.getElementById("uploadLoading");
  const progressText = document.getElementById("progressText");
  const progressCircle = document.querySelector(".progress-circle");
  const cancelButtons = document.querySelectorAll("#cancelUpload");
  const sidebar = document.querySelector(".sidebar");
  const overlay = document.querySelector(".overlay");
  const hamburger = document.querySelector(".hamburger");

  function assetPath(path) {
    const meta = document.querySelector('meta[name="asset-path"]');
    const base = meta ? meta.content : '/';
    return base + path;
  }

  function showPopup(type, message) {
    const icons = {
      error: assetPath("img/close.png"),
      success: assetPath("img/check.png"),
      warning: assetPath("img/warning.png"),
    };
    const popup = document.createElement("div");
    popup.className = `custom-popup ${type}`;
    popup.innerHTML = `
      <div class="popup-content">
        <img src="${icons[type]}" alt="${type}" class="popup-icon">
        <span>${message}</span>
      </div>
    `;
    document.body.appendChild(popup);
    setTimeout(() => popup.classList.add("show"), 10);
    setTimeout(() => {
      popup.classList.add("fade-out");
      setTimeout(() => popup.remove(), 400);
    }, 2500);
  }

  function validateFile(file) {
    const allowedExtensions = ["csv", "xlsx"];
    const fileExt = file.name.split(".").pop().toLowerCase();
    const maxSize = 10 * 1024 * 1024;
    if (!allowedExtensions.includes(fileExt)) {
      showPopup("error", "Format file tidak valid! Hanya mendukung CSV atau XLSX.");
      return false;
    }
    if (file.size > maxSize) {
      showPopup("error", "Ukuran file maksimal 10MB!");
      return false;
    }
    return true;
  }

  function showResult(file) {
    uploadContent.classList.add("hidden");
    uploadResult.classList.remove("hidden");
    selectedFileName.innerHTML = `
      <img src="${assetPath('img/sheet.png')}" alt="File Icon" class="file-icon">
      <span>${file.name}</span>
    `;
  }

  function resetUploadBox() {
    uploadContent.classList.remove("hidden");
    uploadResult.classList.add("hidden");
    uploadLoading.classList.add("hidden");
    if (fileInput) fileInput.value = "";
    if (fileName) fileName.textContent = "";
    if (selectedFileName) selectedFileName.textContent = "";
    if (progressText) progressText.textContent = "0%";
    if (progressCircle)
      progressCircle.style.background = "conic-gradient(var(--telkom-red) 0deg, #ddd 0deg)";
  }

  function setProgress(percent) {
    if (!progressCircle || !progressText) return;
    const deg = percent * 3.6;
    progressCircle.style.background = `conic-gradient(var(--telkom-red) ${deg}deg, #ddd ${deg}deg)`;
    progressText.textContent = `${percent}%`;
  }

  function simulateUpload(file) {
    uploadResult.classList.add("hidden");
    uploadLoading.classList.remove("hidden");
    let progress = 0;
    const interval = setInterval(() => {
      progress += 5;
      setProgress(progress);
      if (progress >= 100) {
        clearInterval(interval);
        setTimeout(() => {
          uploadLoading.classList.add("hidden");
          showPopup("success", `File berhasil diupload: ${file.name}`);
          resetUploadBox();
        }, 600);
      }
    }, 120);
    cancelButtons.forEach(btn => {
      btn.onclick = () => {
        clearInterval(interval);
        uploadLoading.classList.add("hidden");
        showPopup("warning", "Upload dibatalkan.");
        resetUploadBox();
      };
    });
  }

  if (fileInput) {
    fileInput.addEventListener("change", () => {
      if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        if (!validateFile(file)) return resetUploadBox();
        showResult(file);
      } else {
        resetUploadBox();
      }
    });
  }

  if (uploadBox) {
    uploadBox.addEventListener("dragover", e => {
      e.preventDefault();
      uploadBox.classList.add("dragover");
    });
    uploadBox.addEventListener("dragleave", () => {
      uploadBox.classList.remove("dragover");
    });
    uploadBox.addEventListener("drop", e => {
      e.preventDefault();
      uploadBox.classList.remove("dragover");
      if (e.dataTransfer.files.length > 0) {
        const file = e.dataTransfer.files[0];
        if (!validateFile(file)) return;
        fileInput.files = e.dataTransfer.files;
        showResult(file);
      }
    });
  }

  if (btnUpload) {
    btnUpload.addEventListener("click", () => {
      const file = fileInput.files[0];
      if (!file) {
        showPopup("warning", "Tidak ada file yang dipilih!");
        return;
      }
      simulateUpload(file);
    });
  }

  cancelButtons.forEach(btn => {
    btn.addEventListener("click", resetUploadBox);
  });

  function openSidebar() {
    sidebar?.classList.add("active");
    overlay?.classList.remove("hidden");
  }

  function closeSidebar() {
    sidebar?.classList.remove("active");
    overlay?.classList.add("hidden");
  }

  if (hamburger) {
    hamburger.addEventListener("click", () => {
      sidebar?.classList.toggle("active");
      overlay?.classList.toggle("hidden");
    });
  }

  overlay?.addEventListener("click", closeSidebar);

  window.addEventListener("resize", () => {
    if (window.innerWidth > 900) closeSidebar();
  });
});
