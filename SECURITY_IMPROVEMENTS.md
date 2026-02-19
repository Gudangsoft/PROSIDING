# Security Improvements - Prosiding SINACON

Dokumentasi fitur-fitur keamanan yang telah ditambahkan ke aplikasi.

## 📋 Daftar Fitur Keamanan

### 1. **Security Headers Middleware**
Menambahkan HTTP security headers untuk melindungi dari berbagai serangan web.

**Headers yang ditambahkan:**
- `X-Content-Type-Options: nosniff` - Mencegah MIME type sniffing
- `X-Frame-Options: SAMEORIGIN` - Mencegah clickjacking
- `X-XSS-Protection: 1; mode=block` - Proteksi XSS browser
- `Strict-Transport-Security` - Memaksa HTTPS
- `Referrer-Policy` - Kontrol referrer information
- `Content-Security-Policy` - Batasi resource loading

**Lokasi:** `app/Http/Middleware/SecurityHeaders.php`

---

### 2. **Rate Limiting**
Membatasi jumlah request untuk mencegah brute force dan abuse.

**Rate Limits:**
- **Login:** 5 attempts per menit per IP
- **Register:** 3 attempts per jam per IP
- **Password Reset:** 3 attempts per jam per email/IP
- **API:** 60 requests per menit per user/IP
- **File Upload:** 20 uploads per jam per user/IP
- **Email:** 10 emails per jam per user/IP
- **Paper Submission:** 5 submissions per hari per user
- **Review Submission:** 10 reviews per hari per user
- **Form Submission:** 20 per menit per user/IP

**Lokasi:** `app/Providers/RateLimitServiceProvider.php`

**Cara Menggunakan di Route:**
```php
// Contoh penggunaan di route
Route::post('/submit-paper', [PaperController::class, 'submit'])
    ->middleware('throttle:paper-submission');

Route::post('/upload-file', [FileController::class, 'upload'])
    ->middleware('throttle:file-upload');
```

**Cara Menggunakan di Livewire Component:**
```php
use Illuminate\Support\Facades\RateLimiter;

public function submit()
{
    $executed = RateLimiter::attempt(
        'paper-submission:' . auth()->id(),
        5, // max attempts
        function () {
            // Your submission logic here
        },
        86400 // decay in seconds (1 day)
    );

    if (!$executed) {
        session()->flash('error', 'Terlalu banyak submission. Coba lagi besok.');
        return;
    }
}
```

---

### 3. **Activity Logger Middleware**
Mencatat aktivitas user untuk audit trail dan security monitoring.

**Yang dicatat:**
- User ID & Email
- HTTP Method & URL
- IP Address & User Agent
- Status Code
- Request Data (tanpa password)
- Timestamp

**Lokasi:** `app/Http/Middleware/ActivityLogger.php`

**Log disimpan di:** `storage/logs/laravel.log`

---

### 4. **File Upload Validation**
Validasi ketat untuk file upload dengan security checks.

**Lokasi:** `app/Helpers/FileUploadValidator.php`

**Fitur:**
- ✅ Validasi MIME type
- ✅ Validasi ukuran file
- ✅ Validasi extension vs MIME type
- ✅ Deteksi executable files
- ✅ Deteksi PHP code injection
- ✅ Filename sanitization
- ✅ Secure filename generation

**Cara Menggunakan:**

```php
use App\Helpers\FileUploadValidator;

// Validasi paper upload
$validation = FileUploadValidator::validatePaper($file);
if (!$validation['valid']) {
    return back()->withErrors(['file' => $validation['errors']]);
}

// Validasi image upload
$validation = FileUploadValidator::validateImage($file);
if (!$validation['valid']) {
    return back()->withErrors(['image' => $validation['errors']]);
}

// Validasi payment proof
$validation = FileUploadValidator::validatePayment($file);
if (!$validation['valid']) {
    return back()->withErrors(['payment' => $validation['errors']]);
}

// Generate secure filename
$filename = FileUploadValidator::generateSecureFilename($file, 'paper');
// Result: paper_20260218143025_xY9kL2mN.pdf

// Sanitize filename
$safeFilename = FileUploadValidator::sanitizeFilename($userFilename);
```

**Contoh di Livewire Component:**
```php
use App\Helpers\FileUploadValidator;
use Livewire\WithFileUploads;

class SubmitPaper extends Component
{
    use WithFileUploads;

    public $paperFile;

    public function submit()
    {
        $this->validate([
            'paperFile' => 'required|file',
        ]);

        // Validate with security checks
        $validation = FileUploadValidator::validatePaper($this->paperFile);
        
        if (!$validation['valid']) {
            session()->flash('error', implode(' ', $validation['errors']));
            return;
        }

        // Generate secure filename
        $filename = FileUploadValidator::generateSecureFilename(
            $this->paperFile, 
            'paper_' . auth()->id()
        );

        // Store file
        $path = $this->paperFile->storeAs('papers', $filename, 'public');
        
        // Save to database
        // ...
    }
}
```

---

### 5. **Custom Validation Rules**

#### **NoScriptTags** - Mencegah XSS
```php
use App\Rules\NoScriptTags;

$request->validate([
    'content' => ['required', 'string', new NoScriptTags()],
    'title' => ['required', 'string', new NoScriptTags()],
]);
```

#### **SafeFilename** - Mencegah path traversal
```php
use App\Rules\SafeFilename;

$request->validate([
    'filename' => ['required', 'string', new SafeFilename()],
]);
```

#### **StrongPassword** - Password yang kuat
```php
use App\Rules\StrongPassword;

$request->validate([
    'password' => ['required', 'string', new StrongPassword()],
]);
```

**Requirements Strong Password:**
- Minimal 8 karakter
- Minimal 1 huruf besar
- Minimal 1 huruf kecil
- Minimal 1 angka
- Minimal 1 karakter spesial
- Tidak boleh password umum (password123, qwerty, dll)

---

## 🔧 Konfigurasi

### Update `.env` untuk Production

```env
APP_DEBUG=false
APP_ENV=production

# Enable secure cookies
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# HTTPS
SESSION_SECURE_COOKIE=true
```

---

## 📊 Monitoring & Logging

### Melihat Activity Logs
```bash
tail -f storage/logs/laravel.log
```

### Melihat Rate Limit Hits
Rate limit violations akan tercatat di log dengan status code 429.

---

## 🛡️ Best Practices

### 1. Selalu Validasi Input
```php
$validated = $request->validate([
    'email' => ['required', 'email', 'max:255'],
    'name' => ['required', 'string', 'max:255', new NoScriptTags()],
    'content' => ['required', 'string', new NoScriptTags()],
]);
```

### 2. Sanitize Input Sebelum Output
```php
// Di Blade template
{{ $user->name }} // Auto-escaped
{!! strip_tags($content) !!} // Manual sanitize
```

### 3. Gunakan CSRF Protection
```blade
{{-- CSRF token otomatis di form --}}
<form method="POST" action="/submit">
    @csrf
    <!-- form fields -->
</form>
```

### 4. Validate File Uploads
```php
// Selalu gunakan FileUploadValidator
$validation = FileUploadValidator::validatePaper($file);
if (!$validation['valid']) {
    // Handle error
}
```

### 5. Implement Rate Limiting
```php
// Di route
Route::post('/submit', [Controller::class, 'submit'])
    ->middleware('throttle:form-submission');
```

---

## 🚨 Security Checklist

- [x] Security Headers enabled
- [x] Rate limiting implemented
- [x] Activity logging enabled
- [x] File upload validation
- [x] XSS protection (NoScriptTags)
- [x] Path traversal protection (SafeFilename)
- [x] Strong password policy
- [x] CSRF protection (Laravel default)
- [ ] Email verification (optional)
- [ ] Two-factor authentication (Fortify feature)
- [ ] IP whitelist for admin (optional)
- [ ] Database backup automation
- [ ] Malware scanning for uploads (optional)

---

## 📝 Contoh Lengkap: Secure Paper Submission

```php
<?php

namespace App\Livewire\Author;

use App\Helpers\FileUploadValidator;
use App\Rules\NoScriptTags;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubmitPaper extends Component
{
    use WithFileUploads;

    public $title;
    public $abstract;
    public $keywords;
    public $paperFile;

    protected function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255', new NoScriptTags()],
            'abstract' => ['required', 'string', 'max:5000', new NoScriptTags()],
            'keywords' => ['required', 'string', 'max:500', new NoScriptTags()],
            'paperFile' => ['required', 'file'],
        ];
    }

    public function submit()
    {
        // Check rate limit
        $executed = RateLimiter::attempt(
            'paper-submission:' . auth()->id(),
            5,
            function () {
                // Validate form data
                $this->validate();

                // Validate file upload with security checks
                $validation = FileUploadValidator::validatePaper($this->paperFile);
                
                if (!$validation['valid']) {
                    session()->flash('error', implode(' ', $validation['errors']));
                    return false;
                }

                // Generate secure filename
                $filename = FileUploadValidator::generateSecureFilename(
                    $this->paperFile,
                    'paper_' . auth()->id()
                );

                // Store file
                $path = $this->paperFile->storeAs('papers', $filename, 'public');

                // Save to database
                \App\Models\Paper::create([
                    'user_id' => auth()->id(),
                    'title' => strip_tags($this->title),
                    'abstract' => strip_tags($this->abstract),
                    'keywords' => strip_tags($this->keywords),
                    'file_path' => $path,
                    'status' => 'submitted',
                ]);

                session()->flash('success', 'Paper berhasil disubmit!');
                return true;
            },
            86400 // 1 day
        );

        if (!$executed) {
            session()->flash('error', 'Anda telah mencapai batas submission hari ini. Coba lagi besok.');
        }
    }

    public function render()
    {
        return view('livewire.author.submit-paper');
    }
}
```

---

## 📚 Referensi

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Security Best Practices](https://laravel.com/docs/security)
- [Content Security Policy](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP)

---

**Terakhir diupdate:** 18 Februari 2026
