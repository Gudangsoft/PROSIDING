# Sistem Notifikasi - Prosiding LPKD-APJI

## Fitur Notifikasi Lonceng (Bell Icon)

Sistem notifikasi dengan tombol lonceng telah berhasil ditambahkan ke dashboard untuk semua user (Admin, Editor, Reviewer, Author).

### 🔔 Fitur Utama

1. **Tombol Lonceng di Header**
   - Muncul di semua halaman untuk user yang login
   - Badge merah menampilkan jumlah notifikasi yang belum dibaca
   - Dropdown notifikasi dengan UI yang cantik

2. **Jenis Notifikasi**
   - ✅ **Success** - Notifikasi sukses (hijau)
   - ℹ️ **Info** - Informasi umum (biru)
   - ⚠️ **Warning** - Peringatan (kuning)
   - ❌ **Danger/Error** - Kesalahan/Penting (merah)

3. **Fitur Dropdown**
   - Menampilkan 10 notifikasi terbaru
   - Auto-refresh setiap 60 detik
   - Tandai sebagai dibaca (individual/semua)
   - Link action untuk pergi ke halaman terkait
   - Timestamp relatif (contoh: "5 menit yang lalu")

### 📝 Cara Membuat Notifikasi

#### 1. Untuk Single User

```php
use App\Models\Notification;

// Cara 1: Menggunakan helper method
Notification::createForUser(
    userId: $user->id,
    type: 'success',
    title: 'Paper Diterima!',
    message: 'Paper Anda telah diterima untuk publikasi.',
    actionUrl: route('author.paper.detail', $paper->id),
    actionText: 'Lihat Detail'
);

// Cara 2: Menggunakan create biasa
Notification::create([
    'user_id' => $user->id,
    'type' => 'info',
    'title' => 'Review Selesai',
    'message' => 'Review untuk paper Anda telah selesai.',
    'action_url' => route('author.papers'),
    'action_text' => 'Lihat Paper'
]);
```

#### 2. Untuk Multiple Users

```php
use App\Models\Notification;

$authorIds = [1, 2, 3, 4, 5];

Notification::createForUsers(
    userIds: $authorIds,
    type: 'warning',
    title: 'Deadline Mendekat',
    message: 'Deadline submission paper adalah 3 hari lagi!',
    actionUrl: route('author.submit'),
    actionText: 'Submit Paper'
);
```

#### 3. Notifikasi Berdasarkan Role

```php
// Notifikasi untuk semua Admin
$adminIds = User::where('role', 'admin')->pluck('id');
Notification::createForUsers($adminIds, 'info', 'Paper Baru', 'Ada paper baru yang perlu direview.');

// Notifikasi untuk semua Author
$authorIds = User::where('role', 'author')->pluck('id');
Notification::createForUsers($authorIds, 'success', 'Konferensi Dibuka', 'Pendaftaran konferensi telah dibuka!');
```

### 🔧 Integrasi dengan Event

Contoh integrasi di Controller atau Event:

```php
// Ketika paper disubmit
public function submit(Request $request)
{
    $paper = Paper::create($request->validated());
    
    // Notifikasi untuk author
    Notification::createForUser(
        $request->user()->id,
        'success',
        'Paper Berhasil Disubmit',
        'Paper Anda sedang dalam proses review.',
        route('author.paper.detail', $paper),
        'Lihat Status'
    );
    
    // Notifikasi untuk admin
    $adminIds = User::where('role', 'admin')->pluck('id');
    Notification::createForUsers(
        $adminIds,
        'info',
        'Paper Baru Masuk',
        "Paper baru dari {$request->user()->name} perlu direview.",
        route('admin.paper.detail', $paper),
        'Review Sekarang'
    );
    
    return redirect()->route('author.papers')
        ->with('success', 'Paper berhasil disubmit!');
}

// Ketika review selesai
public function completeReview(Review $review)
{
    $review->update(['status' => 'completed']);
    
    // Notifikasi untuk author
    Notification::createForUser(
        $review->paper->user_id,
        'info',
        'Review Selesai',
        'Review untuk paper Anda telah selesai.',
        route('author.paper.detail', $review->paper),
        'Lihat Hasil'
    );
    
    return redirect()->back()->with('success', 'Review berhasil diselesaikan!');
}
```

### 🎨 Komponen yang Ditambahkan

1. **Database**
   - Migration: `2026_02_13_000001_create_notifications_table.php`
   - Model: `App\Models\Notification`
   - Seeder: `Database\Seeders\NotificationSeeder`

2. **Controller & Routes**
   - Controller: `App\Http\Controllers\NotificationController`
   - Routes:
     - `GET /notifications` - Ambil notifikasi
     - `POST /notifications/{id}/read` - Tandai dibaca
     - `POST /notifications/read-all` - Tandai semua dibaca
     - `DELETE /notifications/{id}` - Hapus notifikasi

3. **Frontend**
   - Tombol lonceng di header (`layouts/app.blade.php`)
   - Alpine.js component untuk dropdown
   - Auto-refresh setiap 60 detik
   - Animasi smooth dengan Tailwind CSS

### 🚀 Testing

Untuk testing, jalankan seeder:

```bash
php artisan db:seed --class=NotificationSeeder
```

Seeder akan membuat notifikasi contoh untuk semua user berdasarkan role mereka.

### 📱 Responsive Design

Notifikasi dropdown sudah responsive dan akan menyesuaikan dengan ukuran layar mobile.

### 🔄 Auto Refresh

Notifikasi akan di-refresh otomatis setiap 60 detik untuk memastikan user selalu mendapat update terbaru.

---

**Selamat menggunakan sistem notifikasi! 🎉**
