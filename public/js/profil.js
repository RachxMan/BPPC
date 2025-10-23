document.addEventListener('DOMContentLoaded', () => {
  // === Modal Edit Foto ===
  const editPhotoBtn = document.getElementById('editPhotoBtn');
  const photoModal = document.getElementById('photoModal');
  const closeModalBtn = document.getElementById('closeModalBtn');
  const changePhotoBtn = document.getElementById('changePhotoBtn');
  const deletePhotoBtn = document.getElementById('deletePhotoBtn');
  const photoInput = document.getElementById('photoInput');
  const profilePhoto = document.getElementById('profilePhoto');
  const photoForm = document.getElementById('photoForm');
  const deletePhotoForm = document.getElementById('deletePhotoForm');

  // Buka modal
  editPhotoBtn && editPhotoBtn.addEventListener('click', () => {
    photoModal.classList.remove('hidden');
    photoModal.setAttribute('aria-hidden', 'false');
  });

  // Tutup modal
  closeModalBtn && closeModalBtn.addEventListener('click', () => {
    photoModal.classList.add('hidden');
    photoModal.setAttribute('aria-hidden', 'true');
  });

  // Ganti foto
  changePhotoBtn && changePhotoBtn.addEventListener('click', (e) => {
    e.preventDefault();
    photoInput.click();
  });

  photoInput && photoInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
      profilePhoto.src = ev.target.result;
    };
    reader.readAsDataURL(file);
    setTimeout(() => photoForm.submit(), 300);
  });

  // Hapus foto
  deletePhotoBtn && deletePhotoBtn.addEventListener('click', (e) => {
    e.preventDefault();
    if (confirm('Hapus foto profil?')) deletePhotoForm.submit();
  });

  // Klik luar modal menutup
  photoModal && photoModal.addEventListener('click', (e) => {
    if (e.target === photoModal) {
      photoModal.classList.add('hidden');
      photoModal.setAttribute('aria-hidden', 'true');
    }
  });

  // === Tab Switching (Profile / Password) ===
  const tabs = document.querySelectorAll('.tab');
  const profileTab = document.getElementById('profileTab');
  const passwordTab = document.getElementById('passwordTab');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      if (tab.dataset.target === 'profile') {
        profileTab.style.display = 'block';
        passwordTab.style.display = 'none';
      } else {
        profileTab.style.display = 'none';
        passwordTab.style.display = 'block';
      }
    });
  });
});
