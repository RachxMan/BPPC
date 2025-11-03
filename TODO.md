# TODO: Implement Dashboard Status Dropdowns and Verification

## 1. Update Dashboard View (resources/views/admin/dashboard/index.blade.php)
- [x] Modify "Belum Follow Up" table: Change "AKSI" column to dropdown with "belum" (uncontacted) and "sudah" (contacted)
- [x] Modify "Data Pelanggan" table: Change "STATUS" column to dropdown with "UNPAID" and "PAID"
- [x] Add JavaScript for dropdown changes and popup handling
- [x] Add popup modal for PAID verification

## 2. Update Dashboard Controller (app/Http/Controllers/Admin/DashboardController.php)
- [x] Add method to update status_call for Belum Follow Up
- [x] Add method to update status_bayar for Data Pelanggan
- [x] Modify dataPelanggan query to exclude PAID customers after verification

## 3. Update Routes (routes/web.php)
- [x] Add routes for updating status_call and status_bayar via AJAX

## 4. Test Functionality
- [x] Test dropdown changes update database
- [x] Test PAID verification popup
- [x] Test that PAID customers are removed from dashboard
- [x] Test that "sudah" status filters Belum Follow Up appropriately

## 5. UI/UX Improvements
- [x] Keep simple dropdown styling
- [x] Add hover and focus effects for better UX
- [x] Maintain clean and minimal design
