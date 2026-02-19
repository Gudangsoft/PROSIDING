# Fitur Multiple Payment Methods - Prosiding SINACON

Dokumentasi fitur metode pembayaran dengan multiple options dan nominal.

## 📋 Fitur Yang Ditambahkan

### 1. **Multiple Payment Methods**
Sistem sekarang mendukung beberapa metode pembayaran dengan detail lengkap untuk setiap metode:
- Jenis metode (Bank Transfer, E-Wallet, QRIS, Virtual Account, Tunai, Lainnya)
- Nama Bank/E-Wallet
- Nominal per metode
- No. Rekening / No. HP
- Atas Nama
- Instruksi khusus per metode
- Status aktif/nonaktif per metode

### 2. **Database Migration**
📁 `database/migrations/2026_02_18_000000_add_payment_methods_to_conferences_table.php`

Menambahkan kolom `payment_methods` (JSON) ke tabel `conferences`.

### 3. **Model Update**
📁 `app/Models/Conference.php`

- Menambahkan `payment_methods` ke `$fillable`
- Menambahkan cast `payment_methods => 'array'`

### 4. **Admin Form Update**
📁 `app/Livewire/Admin/ConferenceForm.php`

**Properties:**
```php
public array $paymentMethods = [];
```

**Methods:**
```php
public function addPaymentMethod()    // Tambah metode pembayaran
public function removePaymentMethod($index)  // Hapus metode pembayaran
```

**Data Structure:**
```php
[
    'type' => 'Bank Transfer',              // Jenis metode
    'name' => 'Bank Mandiri',               // Nama bank/e-wallet
    'account_number' => '1234567890',       // No. rekening/HP
    'account_holder' => 'STIFAR',           // Atas nama
    'amount' => 500000,                     // Nominal
    'instructions' => 'Transfer dan kirim bukti via WA', // Instruksi
    'is_active' => true,                    // Status aktif
]
```

### 5. **View Updates**

#### Form Admin (Conference Form)
📁 `resources/views/livewire/admin/conference-form.blade.php`

**Tab: Pricing / Biaya**
- Section "Informasi Pembayaran (Legacy)" - untuk backward compatibility
- Section "Metode Pembayaran" - dengan UI yang lebih lengkap
  - Tombol "Tambah Metode"
  - Form untuk setiap metode dengan:
    - Dropdown jenis metode
    - Input nama bank/e-wallet
    - Input nominal (format: Rp)
    - Input no. rekening/HP
    - Input atas nama
    - Textarea instruksi khusus
    - Checkbox aktif/nonaktif
    - Tombol hapus

#### Payment Upload (Author View)
📁 `resources/views/livewire/author/payment-upload.blade.php`

**Tampilan Metode Pembayaran:**
- Menampilkan semua metode pembayaran yang aktif
- Menampilkan detail lengkap setiap metode:
  - Badge jenis metode (warna biru)
  - Nama bank/e-wallet
  - **Nominal dalam format Rp** (prominent)
  - No. rekening/HP (font mono)
  - Atas nama
  - Instruksi khusus (jika ada)
- Fallback ke informasi pembayaran legacy jika tidak ada metode baru

## 🎨 UI/UX Features

### Admin Form:
- **Gradient background** untuk section payment methods (biru-indigo)
- **Card-based design** untuk setiap metode
- **Badge numbering** untuk metode (#1, #2, dst)
- **Status indicator** (checkbox hijau untuk aktif)
- **Responsive grid layout** (3 kolom di desktop)
- **Empty state** dengan icon dan teks instruksi

### Author Payment View:
- **Gradient card** untuk setiap metode (biru-indigo)
- **Badge warna** untuk jenis metode
- **Prominent nominal** dengan format rupiah dan warna biru
- **Font mono** untuk no. rekening (mudah dibaca)
- **Instruksi terpisah** dengan border atas
- **Responsive layout**

## 📝 Cara Penggunaan

### 1. Admin - Menambah Metode Pembayaran

1. Login sebagai Admin
2. Buka menu **Admin → Kegiatan Prosiding**
3. Edit atau buat conference baru
4. Buka tab **Biaya**
5. Scroll ke section **"Metode Pembayaran"**
6. Klik tombol **"Tambah Metode"**
7. Isi data:
   - **Jenis Metode**: Pilih dari dropdown (Bank Transfer, E-Wallet, QRIS, dll)
   - **Nama Bank/E-Wallet**: Misal "Bank Mandiri", "OVO", "Dana"
   - **Nominal**: Masukkan jumlah (misal 500000 untuk Rp 500.000)
   - **No. Rekening/HP**: Nomor rekening atau nomor HP untuk e-wallet
   - **Atas Nama**: Nama pemilik rekening
   - **Instruksi Khusus**: Instruksi tambahan (opsional)
   - **Aktif**: Centang untuk mengaktifkan metode ini
8. Klik **"Simpan"**

**Contoh Data:**
```
Metode #1:
- Jenis: Bank Transfer
- Nama: Bank Mandiri
- Nominal: 500000
- No. Rekening: 1234567890
- Atas Nama: STIFAR
- Instruksi: Silahkan lakukan pembayaran dan konfirmasi via WA
- Status: ✓ Aktif

Metode #2:
- Jenis: E-Wallet
- Nama: OVO
- Nominal: 475000 (diskon untuk e-wallet)
- No. HP: 08976543210
- Atas Nama: STIFAR Conference
- Instruksi: Kirim ke nomor OVO dan screenshot bukti pembayaran
- Status: ✓ Aktif

Metode #3:
- Jenis: QRIS
- Nama: QRIS STIFAR
- Nominal: 500000
- Instruksi: Scan QRIS dan upload screenshot bukti pembayaran
- Status: ✓ Aktif
```

### 2. Author - Melihat Metode Pembayaran

1. Login sebagai Author
2. Buka **Paper → Detail Paper**
3. Klik tab **"Pembayaran"** atau navigasi ke upload pembayaran
4. Di section **"Detail Invoice"**, lihat:
   - **"Metode Pembayaran Tersedia"**
   - Semua metode yang aktif akan ditampilkan
   - Setiap metode menampilkan:
     - Badge jenis metode
     - Nama bank/e-wallet
     - **Nominal (Rp xxx.xxx)**
     - No. Rekening/HP
     - Atas Nama
     - Instruksi khusus
5. Pilih metode yang diinginkan
6. Lakukan pembayaran sesuai nominal dan instruksi
7. Upload bukti pembayaran

### 3. Dropdown Metode Pembayaran Saat Upload

Saat upload bukti pembayaran, Author bisa memilih metode dari dropdown:
- Transfer Bank
- QRIS
- E-Wallet
- Virtual Account

## 🔄 Backward Compatibility

Sistem tetap mendukung metode lama:
- **Field Legacy** di Conference:
  - `payment_bank_name`
  - `payment_bank_account`
  - `payment_account_holder`
  - `payment_contact_phone`
  - `payment_instructions`
  
- **Tampilan Legacy**:
  - Jika tidak ada payment methods baru, tampilkan informasi legacy
  - Section "Informasi Pembayaran (Legacy)" di admin form tetap ada
  
- **Migration Safe**:
  - Kolom lama tidak diubah
  - Kolom baru `payment_methods` nullable
  - Data lama tetap berfungsi

## 💡 Keuntungan Fitur Ini

1. **Fleksibilitas Pembayaran**
   - Support multiple payment methods
   - Bisa set nominal berbeda per metode (misal diskon e-wallet)
   
2. **Informasi Jelas**
   - Author langsung lihat semua opsi pembayaran
   - Nominal jelas untuk setiap metode
   - Instruksi khusus per metode
   
3. **Manajemen Mudah**
   - Admin bisa aktifkan/nonaktifkan metode tertentu
   - Update informasi per metode tanpa mempengaruhi yang lain
   - Tambah/hapus metode kapan saja
   
4. **Professional UI**
   - Tampilan modern dengan gradient dan badges
   - Informasi terstruktur dan mudah dibaca
   - Responsif di semua device

## 🧪 Testing

### Test Scenario 1: Tambah Multiple Payment Methods
1. Login sebagai admin
2. Edit conference
3. Tambah 3 metode pembayaran berbeda
4. Set nominal berbeda untuk setiap metode
5. Aktifkan metode 1 dan 2, nonaktifkan metode 3
6. Save

**Expected:**
- 3 metode tersimpan di database
- Hanya metode 1 dan 2 yang muncul di form payment author
- Nominal ditampilkan dengan benar

### Test Scenario 2: Author View Payment Methods
1. Login sebagai author
2. Buka paper detail → payment
3. Lihat section "Metode Pembayaran Tersedia"

**Expected:**
- Semua metode aktif ditampilkan
- Nominal dalam format Rupiah
- Detail lengkap setiap metode terlihat jelas

### Test Scenario 3: Backward Compatibility
1. Conference lama tanpa payment_methods baru
2. Author akses payment upload

**Expected:**
- Tampil informasi legacy (bank name, account, dll)
- Tidak error meski payment_methods = null

## 📊 Database Schema

```sql
ALTER TABLE conferences 
ADD COLUMN payment_methods JSON NULL 
AFTER payment_instructions;
```

**Sample Data:**
```json
[
    {
        "type": "Bank Transfer",
        "name": "Bank Mandiri",
        "account_number": "1234567890",
        "account_holder": "STIFAR",
        "amount": 500000,
        "instructions": "Transfer dan kirim bukti via WA",
        "is_active": true
    },
    {
        "type": "E-Wallet",
        "name": "OVO",
        "account_number": "08976543210",
        "account_holder": "STIFAR",
        "amount": 475000,
        "instructions": "Kirim ke nomor OVO",
        "is_active": true
    }
]
```

## 🚀 Future Enhancements

- [ ] Payment gateway integration (Midtrans, Xendit)
- [ ] Auto-calculate total dengan pilihan metode
- [ ] QR Code generator untuk payment
- [ ] Payment reminder via email
- [ ] Payment analytics dashboard
- [ ] Multi-currency support
- [ ] Payment installment

---

**Created:** 18 Februari 2026  
**Version:** 1.0
