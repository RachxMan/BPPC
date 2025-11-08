# TODO: Perbaikan Chart Kinerja CA dan Progress Collection

## Tugas Utama
- Perbaiki chart Kinerja CA agar berdasarkan tanggal kontak dilakukan (`contact_date`), bukan tanggal diubah (`updated_at`)
- Perbaiki Progress Collection mingguan agar berdasarkan tanggal kontak dilakukan

## Langkah-langkah
- [x] Buat migration untuk kolom `contact_date`
- [x] Tambahkan `contact_date` ke fillable di model CaringTelepon
- [x] Ubah query DashboardController untuk menggunakan `contact_date`
- [x] Ubah CaringController untuk set `contact_date` setiap kali status_call diupdate
- [x] Populate `contact_date` untuk data existing menggunakan `updated_at`
- [ ] Test dashboard untuk memastikan chart menampilkan data berdasarkan tanggal kontak asli

## Status
- [x] Analisis masalah selesai
- [x] Plan disetujui pengguna
- [x] Implementasi perubahan
- [x] Testing - Chart sekarang menggunakan contact_date untuk semua metrik
