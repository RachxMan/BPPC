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
  const cancelUpload = document.getElementById("cancelUpload");
  const sidebar = document.querySelector(".sidebar");
  const overlay = document.querySelector(".overlay");
  const hamburger = document.querySelector(".hamburger");

  function assetPath(path) {
    const base = document.querySelector('meta[name="asset-path"]').content;
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
    return allowedExtensions.includes(fileExt);
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
    fileInput.value = "";
    fileName.textContent = "";
    selectedFileName.textContent = "";
    progressText.textContent = "0%";
    if (progressCircle)
      progressCircle.style.background = "conic-gradient(var(--telkom-red) 0deg, #ddd 0deg)";
  }

  function simulateUpload(file) {
    uploadResult.classList.add("hidden");
    uploadLoading.classList.remove("hidden");

    let progress = 0;
    const interval = setInterval(() => {
      progress += 5;
      progressText.textContent = `${progress}%`;
      if (progressCircle) {
        progressCircle.style.transition = "background 0.3s ease";
        progressCircle.style.background = `conic-gradient(var(--telkom-red) ${progress * 3.6}deg, #ddd ${progress * 3.6}deg)`;
      }

      if (progress >= 100) {
        clearInterval(interval);
        setTimeout(() => {
          uploadLoading.classList.add("hidden");
          showPopup("success", "File berhasil diupload: " + file.name);
          resetUploadBox();
        }, 600);
      }
    }, 120);

    cancelUpload.onclick = () => {
      clearInterval(interval);
      uploadLoading.classList.add("hidden");
      showPopup("warning", "Upload dibatalkan.");
      resetUploadBox();
    };
  }

  fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) {
      const file = fileInput.files[0];
      if (!validateFile(file)) {
        showPopup("error", "Format file tidak valid! Hanya mendukung CSV atau XLSX.");
        resetUploadBox();
        return;
      }
      showResult(file);
    } else {
      resetUploadBox();
    }
  });

  uploadBox.addEventListener("dragover", (e) => {
    e.preventDefault();
    uploadBox.classList.add("dragover");
  });

  uploadBox.addEventListener("dragleave", () => {
    uploadBox.classList.remove("dragover");
  });

  uploadBox.addEventListener("drop", (e) => {
    e.preventDefault();
    uploadBox.classList.remove("dragover");
    if (e.dataTransfer.files.length > 0) {
      const file = e.dataTransfer.files[0];
      if (!validateFile(file)) {
        showPopup("error", "Format file tidak valid! Hanya mendukung CSV atau XLSX.");
        return;
      }
      fileInput.files = e.dataTransfer.files;
      showResult(file);
    }
  });

  btnUpload.addEventListener("click", () => {
    const file = fileInput.files[0];
    if (!file) {
      showPopup("warning", "Tidak ada file yang dipilih!");
      return;
    }
    simulateUpload(file);
  });

  function openSidebar() {
    sidebar.classList.add("open");
    overlay.classList.remove("hidden");
    overlay.classList.add("show");
  }

  function closeSidebar() {
    sidebar.classList.remove("open");
    overlay.classList.add("hidden");
    overlay.classList.remove("show");
  }

  hamburger.addEventListener("click", () => {
    if (sidebar.classList.contains("open")) closeSidebar();
    else openSidebar();
  });

  overlay.addEventListener("click", closeSidebar);

  function handleResize() {
    if (window.innerWidth > 900) {
      sidebar.classList.remove("open");
      overlay.classList.add("hidden");
    }
  }

  window.addEventListener("resize", handleResize);
});
