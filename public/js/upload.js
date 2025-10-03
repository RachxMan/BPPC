document.addEventListener("DOMContentLoaded", () => {
  const fileInput = document.getElementById("fileInput");
  const fileName = document.getElementById("fileName");
  const uploadBox = document.getElementById("uploadBox");

  function validateFile(file) {
    const allowedExtensions = ["csv", "xlsx"];
    const fileExt = file.name.split(".").pop().toLowerCase();
    return allowedExtensions.includes(fileExt);
  }

  fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) {
      if (!validateFile(fileInput.files[0])) {
        alert("❌ Format file tidak valid! Hanya mendukung CSV atau XLSX.");
        fileInput.value = "";
        fileName.textContent = "";
        return;
      }
      fileName.textContent = "File dipilih: " + fileInput.files[0].name;
    } else {
      fileName.textContent = "";
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
        alert("❌ Format file tidak valid! Hanya mendukung CSV atau XLSX.");
        return;
      }

      fileInput.files = e.dataTransfer.files;
      fileName.textContent = "File dipilih: " + file.name;
    }
  });

const sidebar = document.getElementById('sidebar');
const hamburger = document.getElementById('hamburger');
const overlay = document.getElementById('overlay');

function openSidebar(){
  sidebar.classList.add('open');
  overlay.classList.remove('hidden'); overlay.classList.add('show');
}
function closeSidebar(){
  sidebar.classList.remove('open');
  overlay.classList.add('hidden'); overlay.classList.remove('show');
}

hamburger.addEventListener('click', () => {
  if (sidebar.classList.contains('open')) closeSidebar(); else openSidebar();
});
overlay.addEventListener('click', closeSidebar);

function handleResize(){
  if (window.innerWidth > 900){
    sidebar.classList.remove('open');
    overlay.classList.add('hidden');
  }
}
window.addEventListener('resize', handleResize);
});
