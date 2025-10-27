document.addEventListener("DOMContentLoaded", () => {
    // === Modal Edit Foto ===
    const editPhotoBtn = document.getElementById("editPhotoBtn");
    const photoModal = document.getElementById("photoModal");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const changePhotoBtn = document.getElementById("changePhotoBtn");
    const deletePhotoBtn = document.getElementById("deletePhotoBtn");
    const photoInput = document.getElementById("photoInput");
    const profilePhoto = document.getElementById("profilePhoto");

    let selectedFile = null; // Store selected file for upload

    // === Modal Konfirmasi dan Notifikasi ===
    const confirmModal = document.getElementById("confirmModal");
    const confirmTitle = document.getElementById("confirmTitle");
    const confirmMessage = document.getElementById("confirmMessage");
    const confirmYesBtn = document.getElementById("confirmYesBtn");
    const confirmNoBtn = document.getElementById("confirmNoBtn");
    const notificationModal = document.getElementById("notificationModal");
    const notificationTitle = document.getElementById("notificationTitle");
    const notificationMessage = document.getElementById("notificationMessage");
    const notificationCloseBtn = document.getElementById(
        "notificationCloseBtn"
    );

    // Function to show notification modal
    function showNotification(title, message) {
        notificationTitle.textContent = title;
        notificationMessage.textContent = message;
        notificationModal.classList.remove("hidden");
        notificationModal.setAttribute("aria-hidden", "false");
    }

    // Function to hide notification modal
    function hideNotification() {
        notificationModal.classList.add("hidden");
        notificationModal.setAttribute("aria-hidden", "true");
    }

    // Buka modal
    editPhotoBtn &&
        editPhotoBtn.addEventListener("click", () => {
            photoModal.classList.remove("hidden");
            photoModal.setAttribute("aria-hidden", "false");
        });

    // Tutup modal
    closeModalBtn &&
        closeModalBtn.addEventListener("click", () => {
            photoModal.classList.add("hidden");
            photoModal.setAttribute("aria-hidden", "true");
        });

    // Ganti foto
    changePhotoBtn &&
        changePhotoBtn.addEventListener("click", (e) => {
            e.preventDefault();
            photoInput.click();
        });

    photoInput &&
        photoInput.addEventListener("change", (e) => {
            selectedFile = e.target.files[0];
            if (!selectedFile) return;
            const reader = new FileReader();
            reader.onload = (ev) => {
                // Preview the photo on profile page only
                profilePhoto.src = ev.target.result;
            };
            reader.readAsDataURL(selectedFile);
            // Close the modal immediately after selecting
            photoModal.classList.add("hidden");
            photoModal.setAttribute("aria-hidden", "true");
            // Reset delete flag
            document.getElementById("deletePhotoInput").value = "0";
            // Do not submit automatically, just preview
        });

    // Hapus foto
    deletePhotoBtn &&
        deletePhotoBtn.addEventListener("click", (e) => {
            e.preventDefault();
            // Show confirmation modal for delete
            confirmTitle.textContent = "Hapus Foto";
            confirmMessage.textContent =
                "Apakah Anda yakin ingin menghapus foto profil?";
            confirmModal.classList.remove("hidden");
            confirmModal.setAttribute("aria-hidden", "false");

            // Handle delete confirmation
            const handleDeleteConfirm = () => {
                confirmModal.classList.add("hidden");
                confirmModal.setAttribute("aria-hidden", "true");
                // Set delete flag and submit main form
                document.getElementById("deletePhotoInput").value = "1";
                profileForm.submit();
                confirmYesBtn.removeEventListener("click", handleDeleteConfirm);
            };

            confirmYesBtn.addEventListener("click", handleDeleteConfirm);
        });

    // Tutup modal konfirmasi
    confirmNoBtn &&
        confirmNoBtn.addEventListener("click", () => {
            confirmModal.classList.add("hidden");
            confirmModal.setAttribute("aria-hidden", "true");
        });

    // Tutup modal notifikasi
    notificationCloseBtn &&
        notificationCloseBtn.addEventListener("click", hideNotification);

    // Klik luar modal menutup
    confirmModal &&
        confirmModal.addEventListener("click", (e) => {
            if (e.target === confirmModal) {
                confirmModal.classList.add("hidden");
                confirmModal.setAttribute("aria-hidden", "true");
            }
        });

    notificationModal &&
        notificationModal.addEventListener("click", (e) => {
            if (e.target === notificationModal) {
                hideNotification();
            }
        });

    // Klik luar modal menutup
    photoModal &&
        photoModal.addEventListener("click", (e) => {
            if (e.target === photoModal) {
                photoModal.classList.add("hidden");
                photoModal.setAttribute("aria-hidden", "true");
            }
        });

    // === Tab Switching (Profile / Password) ===
    const tabs = document.querySelectorAll(".tab");
    const profileTab = document.getElementById("profileTab");
    const passwordTab = document.getElementById("passwordTab");

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            tabs.forEach((t) => t.classList.remove("active"));
            tab.classList.add("active");

            if (tab.dataset.target === "profile") {
                profileTab.style.display = "block";
                passwordTab.style.display = "none";
            } else {
                profileTab.style.display = "none";
                passwordTab.style.display = "block";
            }
        });
    });

    // === Handle Profile Form Submission ===
    const profileForm = document.getElementById("profileForm");
    const saveBtn = document.getElementById("saveBtn");

    // Show confirmation modal on save button click
    saveBtn &&
        saveBtn.addEventListener("click", (e) => {
            e.preventDefault();
            confirmTitle.textContent = "Konfirmasi";
            confirmMessage.textContent =
                "Apakah Anda yakin ingin menyimpan perubahan?";
            confirmModal.classList.remove("hidden");
            confirmModal.setAttribute("aria-hidden", "false");
        });

    // === Handle Password Form Submission ===
    const passwordForm = document.getElementById("passwordForm");
    const savePasswordBtn = document.getElementById("savePasswordBtn");

    // Show confirmation modal on save password button click
    savePasswordBtn &&
        savePasswordBtn.addEventListener("click", (e) => {
            e.preventDefault();
            confirmTitle.textContent = "Konfirmasi";
            confirmMessage.textContent =
                "Apakah Anda yakin ingin mengubah password?";
            confirmModal.classList.remove("hidden");
            confirmModal.setAttribute("aria-hidden", "false");
        });

    // Single confirm handler for both forms
    confirmYesBtn &&
        confirmYesBtn.addEventListener("click", () => {
            confirmModal.classList.add("hidden");
            confirmModal.setAttribute("aria-hidden", "true");

            // Check which form is active
            const profileTab = document.getElementById("profileTab");
            const passwordTab = document.getElementById("passwordTab");

            if (profileTab.style.display !== "none") {
                submitProfileForm();
            } else if (passwordTab.style.display !== "none") {
                submitPasswordForm();
            }
        });

    function submitProfileForm() {
        const formData = new FormData(profileForm);

        // If there's a selected file, append it to FormData
        if (selectedFile) {
            formData.set("profile_photo", selectedFile);
        }

        fetch(profileForm.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Update all profile photos with the new URL
                    document
                        .querySelectorAll(".profile-photo")
                        .forEach((img) => {
                            img.src = data.profile_photo_url;
                        });
                    showNotification("Berhasil", "Profil berhasil diperbarui!");
                    selectedFile = null; // Reset selected file after successful upload
                } else if (data.errors) {
                    // Show validation errors
                    let errorMessages = [];
                    for (let field in data.errors) {
                        errorMessages.push(data.errors[field].join(", "));
                    }
                    showNotification(
                        "Kesalahan Validasi",
                        errorMessages.join("\n")
                    );
                } else {
                    showNotification(
                        "Kesalahan",
                        "Terjadi kesalahan saat memperbarui profil."
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showNotification(
                    "Kesalahan",
                    "Terjadi kesalahan saat memperbarui profil."
                );
            });
    }

    function submitPasswordForm() {
        const formData = new FormData(passwordForm);

        fetch(passwordForm.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showNotification("Berhasil", data.message);
                    passwordForm.reset(); // Reset form after success
                } else if (data.errors) {
                    // Show validation errors
                    let errorMessages = [];
                    for (let field in data.errors) {
                        errorMessages.push(data.errors[field].join(", "));
                    }
                    showNotification(
                        "Kesalahan Validasi",
                        errorMessages.join("\n")
                    );
                } else {
                    showNotification(
                        "Kesalahan",
                        "Terjadi kesalahan saat memperbarui password."
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showNotification(
                    "Kesalahan",
                    "Terjadi kesalahan saat memperbarui password."
                );
            });
    }
});
