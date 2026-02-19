<?php

/**
 * CONTOH IMPLEMENTASI SECURITY FEATURES
 * File ini berisi contoh praktis penggunaan fitur-fitur keamanan
 * 
 * Referensi: SECURITY_IMPROVEMENTS.md
 */

namespace App\Examples;

use App\Helpers\FileUploadValidator;
use App\Rules\NoScriptTags;
use App\Rules\SafeFilename;
use App\Rules\StrongPassword;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Contoh 1: Secure Paper Submission
 */
class SecurePaperSubmissionExample extends Component
{
    use WithFileUploads;

    public $title;
    public $abstract;
    public $file;

    protected function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255', new NoScriptTags()],
            'abstract' => ['required', 'string', new NoScriptTags()],
            'file' => ['required', 'file'],
        ];
    }

    public function submit()
    {
        // 1. Check rate limit first
        $executed = RateLimiter::attempt(
            'paper-submission:' . auth()->id(),
            5, // max 5 submissions per day
            function () {
                return $this->processSubmission();
            },
            86400 // 1 day in seconds
        );

        if (!$executed) {
            session()->flash('error', 'Anda telah mencapai batas submission hari ini.');
            return;
        }
    }

    private function processSubmission()
    {
        // 2. Validate form data (includes XSS protection)
        $this->validate();

        // 3. Validate file upload with security checks
        $validation = FileUploadValidator::validatePaper($this->file);
        
        if (!$validation['valid']) {
            session()->flash('error', implode(' ', $validation['errors']));
            return false;
        }

        // 4. Generate secure filename
        $filename = FileUploadValidator::generateSecureFilename(
            $this->file,
            'paper_' . auth()->id()
        );

        // 5. Store file
        $path = $this->file->storeAs('papers', $filename, 'public');

        // 6. Save to database (data already sanitized by validation)
        \App\Models\Paper::create([
            'user_id' => auth()->id(),
            'title' => strip_tags($this->title),
            'abstract' => strip_tags($this->abstract),
            'file_path' => $path,
            'status' => 'submitted',
        ]);

        session()->flash('success', 'Paper berhasil disubmit!');
        return true;
    }

    public function render()
    {
        return view('livewire.examples.secure-paper-submission');
    }
}

/**
 * Contoh 2: Secure Image Upload
 */
class SecureImageUploadExample extends Component
{
    use WithFileUploads;

    public $photo;

    public function upload()
    {
        // Apply rate limiting
        if (!RateLimiter::attempt('file-upload:' . auth()->id(), 20, function () {}, 3600)) {
            session()->flash('error', 'Terlalu banyak upload. Coba lagi dalam 1 jam.');
            return;
        }

        $this->validate([
            'photo' => 'required|file',
        ]);

        // Validate image with security checks
        $validation = FileUploadValidator::validateImage($this->photo);
        
        if (!$validation['valid']) {
            session()->flash('error', implode(' ', $validation['errors']));
            return;
        }

        // Generate secure filename
        $filename = FileUploadValidator::generateSecureFilename($this->photo, 'profile');
        
        // Store
        $path = $this->photo->storeAs('photos', $filename, 'public');
        
        // Update user profile
        auth()->user()->update(['photo' => $path]);
        
        session()->flash('success', 'Foto berhasil diupload!');
    }

    public function render()
    {
        return view('livewire.examples.secure-image-upload');
    }
}

/**
 * Contoh 3: Form dengan Input Sanitization
 */
class SecureFormExample extends Component
{
    public $name;
    public $email;
    public $message;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', new NoScriptTags()],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000', new NoScriptTags()],
        ];
    }

    public function submit()
    {
        // Rate limit form submissions
        if (!RateLimiter::attempt('form-submission:' . request()->ip(), 20, function () {}, 60)) {
            session()->flash('error', 'Terlalu banyak pengiriman. Tunggu sebentar.');
            return;
        }

        $validated = $this->validate();

        // Additional sanitization (defense in depth)
        $cleanData = [
            'name' => strip_tags($validated['name']),
            'email' => filter_var($validated['email'], FILTER_SANITIZE_EMAIL),
            'message' => strip_tags($validated['message']),
        ];

        // Process form...
        
        session()->flash('success', 'Form berhasil dikirim!');
    }

    public function render()
    {
        return view('livewire.examples.secure-form');
    }
}

/**
 * Contoh 4: Password Update dengan Strong Password Rule
 */
class SecurePasswordUpdateExample extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected function rules()
    {
        return [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'confirmed', new StrongPassword()],
        ];
    }

    public function updatePassword()
    {
        $this->validate();

        // Verify current password
        if (!\Illuminate\Support\Facades\Hash::check($this->current_password, auth()->user()->password)) {
            $this->addError('current_password', 'Password saat ini tidak sesuai.');
            return;
        }

        // Update password
        auth()->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($this->new_password)
        ]);

        // Reset form
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        
        session()->flash('success', 'Password berhasil diupdate!');
    }

    public function render()
    {
        return view('livewire.examples.secure-password-update');
    }
}

/**
 * Contoh 5: File Upload dengan Filename Validation
 */
class SecureFileManagerExample extends Component
{
    use WithFileUploads;

    public $file;
    public $customFilename;

    protected function rules()
    {
        return [
            'file' => ['required', 'file'],
            'customFilename' => ['nullable', 'string', 'max:255', new SafeFilename()],
        ];
    }

    public function upload()
    {
        $this->validate();

        // Validate file
        $validation = FileUploadValidator::validatePaper($this->file);
        
        if (!$validation['valid']) {
            session()->flash('error', implode(' ', $validation['errors']));
            return;
        }

        // Use custom filename or generate secure one
        if ($this->customFilename) {
            $filename = FileUploadValidator::sanitizeFilename($this->customFilename);
        } else {
            $filename = FileUploadValidator::generateSecureFilename($this->file);
        }

        $path = $this->file->storeAs('files', $filename, 'public');
        
        session()->flash('success', 'File berhasil diupload dengan nama: ' . $filename);
    }

    public function render()
    {
        return view('livewire.examples.secure-file-manager');
    }
}

/**
 * Contoh 6: API dengan Rate Limiting
 */
class ApiExample
{
    public function __construct()
    {
        // Di routes/web.php atau routes/api.php:
        // Route::post('/api/submit', [ApiExample::class, 'submit'])
        //     ->middleware('throttle:api');
    }

    public function submit(\Illuminate\Http\Request $request)
    {
        // Additional rate limiting by user
        $userId = $request->user()?->id ?: $request->ip();
        
        if (!RateLimiter::attempt("api-submit:{$userId}", 10, function () {}, 3600)) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.'
            ], 429);
        }

        // Process API request...
        
        return response()->json([
            'message' => 'Success',
            'data' => []
        ]);
    }
}

/**
 * Contoh 7: Bulk Upload dengan Validation
 */
class SecureBulkUploadExample extends Component
{
    use WithFileUploads;

    public $files = [];

    public function uploadMultiple()
    {
        // Validate array of files
        $this->validate([
            'files.*' => 'required|file',
        ]);

        $uploaded = [];
        $errors = [];

        foreach ($this->files as $index => $file) {
            // Validate each file
            $validation = FileUploadValidator::validatePaper($file);
            
            if (!$validation['valid']) {
                $errors[] = "File #" . ($index + 1) . ": " . implode(', ', $validation['errors']);
                continue;
            }

            // Generate secure filename
            $filename = FileUploadValidator::generateSecureFilename($file, 'bulk');
            
            // Store
            $path = $file->storeAs('bulk-uploads', $filename, 'public');
            $uploaded[] = $filename;
        }

        if (!empty($errors)) {
            session()->flash('error', 'Beberapa file gagal diupload: ' . implode('; ', $errors));
        }

        if (!empty($uploaded)) {
            session()->flash('success', count($uploaded) . ' file berhasil diupload!');
        }
    }

    public function render()
    {
        return view('livewire.examples.secure-bulk-upload');
    }
}
