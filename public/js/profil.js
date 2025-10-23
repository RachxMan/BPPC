document.addEventListener("DOMContentLoaded", () => {
    // === Modal Edit Foto ===
    const editPhotoBtn = document.getElementById("editPhotoBtn");
    const photoModal = document.getElementById("photoModal");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const changePhotoBtn = document.getElementById("changePhotoBtn");
    const deletePhotoBtn = document.getElementById("deletePhotoBtn");
    const photoInput = document.getElementById("photoInput");
    const profilePhoto = document.getElementById("profilePhoto");
    const photoForm = document.getElementById("photoForm");
    const deletePhotoForm = document.getElementById("deletePhotoForm");

    let selectedFile = null; // Store selected file for upload

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
            if (confirm("Hapus foto profil?")) {
                // Set delete flag and submit main form
                document.getElementById("deletePhotoInput").value = "1";
                profileForm.submit();
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

    profileForm &&
        profileForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(profileForm);

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
                        alert("Profil berhasil diperbarui!");
                    } else if (data.errors) {
                        // Show validation errors
                        let errorMessages = [];
                        for (let field in data.errors) {
                            errorMessages.push(data.errors[field].join(", "));
                        }
                        alert(
                            "Kesalahan validasi:\n" + errorMessages.join("\n")
                        );
                    } else {
                        alert("Terjadi kesalahan saat memperbarui profil.");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan saat memperbarui profil.");
                });
        });
});
