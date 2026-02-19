<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SetupController extends Controller
{
    // Token keamanan - ganti sebelum upload ke server!
    private const SETUP_TOKEN = 'setup-prosiding;

    // File penanda bahwa setup sudah selesai
    private function completedFlagPath(): string
    {
        return storage_path('app/setup_complete.lock');
    }

    private function isCompleted(): bool
    {
        return file_exists($this->completedFlagPath());
    }

    private function checkToken(Request $request): bool
    {
        return $request->session()->get('setup_authenticated') === true;
    }

    // -------------------------------------------------------------------------
    // GET /setup  →  Halaman utama wizard
    // -------------------------------------------------------------------------
    public function index(Request $request)
    {
        if ($this->isCompleted()) {
            return view('setup.index', ['completed' => true]);
        }
        return view('setup.index', ['completed' => false]);
    }

    // -------------------------------------------------------------------------
    // POST /setup/auth  →  Verifikasi token akses
    // -------------------------------------------------------------------------
    public function auth(Request $request)
    {
        $token = $request->input('token');
        if ($token === self::SETUP_TOKEN) {
            $request->session()->put('setup_authenticated', true);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Token tidak valid.'], 401);
    }

    // -------------------------------------------------------------------------
    // POST /setup/requirements  →  Cek kebutuhan sistem
    // -------------------------------------------------------------------------
    public function requirements(Request $request)
    {
        if (!$this->checkToken($request)) {
            return response()->json(['success' => false, 'message' => 'Tidak terautentikasi.'], 401);
        }

        $checks = [];

        // PHP Version
        $phpVersion = PHP_VERSION;
        $checks[] = [
            'label' => 'PHP Version (' . $phpVersion . ')',
            'pass'  => version_compare($phpVersion, '8.2.0', '>='),
            'note'  => 'Minimal PHP 8.2',
        ];

        // Required Extensions
        $extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo', 'gd'];
        foreach ($extensions as $ext) {
            $checks[] = [
                'label' => 'Extension: ' . $ext,
                'pass'  => extension_loaded($ext),
                'note'  => '',
            ];
        }

        // Folder writable
        $writablePaths = [
            'storage/app'         => storage_path('app'),
            'storage/framework'   => storage_path('framework'),
            'storage/logs'        => storage_path('logs'),
            'bootstrap/cache'     => base_path('bootstrap/cache'),
        ];
        foreach ($writablePaths as $label => $path) {
            $checks[] = [
                'label' => 'Writable: ' . $label,
                'pass'  => is_writable($path),
                'note'  => 'Folder harus bisa ditulis (chmod 775)',
            ];
        }

        // .env writable
        $envPath = base_path('.env');
        $envExists = file_exists($envPath);
        $checks[] = [
            'label' => '.env file ' . ($envExists ? '(ada, writable)' : '(belum ada)'),
            'pass'  => !$envExists || is_writable($envPath),
            'note'  => 'File .env harus bisa ditulis',
        ];

        $allPass = collect($checks)->every(fn($c) => $c['pass']);

        return response()->json(['success' => true, 'checks' => $checks, 'allPass' => $allPass]);
    }

    // -------------------------------------------------------------------------
    // POST /setup/save-env  →  Tulis file .env
    // -------------------------------------------------------------------------
    public function saveEnv(Request $request)
    {
        if (!$this->checkToken($request)) {
            return response()->json(['success' => false, 'message' => 'Tidak terautentikasi.'], 401);
        }

        $data = $request->validate([
            'app_name'     => 'required|string',
            'app_url'      => 'required|url',
            'app_env'      => 'required|in:local,production,staging',
            'app_debug'    => 'required|in:true,false',
            'db_host'      => 'required|string',
            'db_port'      => 'required|string',
            'db_database'  => 'required|string',
            'db_username'  => 'required|string',
            'db_password'  => 'nullable|string',
            'mail_mailer'  => 'required|string',
            'mail_host'    => 'nullable|string',
            'mail_port'    => 'nullable|string',
            'mail_username'=> 'nullable|string',
            'mail_password'=> 'nullable|string',
            'mail_from_address' => 'nullable|email',
            'mail_from_name'    => 'nullable|string',
        ]);

        // Baca template .env.example atau .env yang sudah ada
        $envPath        = base_path('.env');
        $envExamplePath = base_path('.env.example');

        $template = file_exists($envExamplePath) ? file_get_contents($envExamplePath) : '';

        // Jika .env sudah ada, gunakan isinya sebagai base
        if (file_exists($envPath)) {
            $template = file_get_contents($envPath);
        }

        $map = [
            'APP_NAME'            => '"' . $data['app_name'] . '"',
            'APP_ENV'             => $data['app_env'],
            'APP_DEBUG'           => $data['app_debug'],
            'APP_URL'             => $data['app_url'],
            'DB_CONNECTION'       => 'mysql',
            'DB_HOST'             => $data['db_host'],
            'DB_PORT'             => $data['db_port'],
            'DB_DATABASE'         => $data['db_database'],
            'DB_USERNAME'         => $data['db_username'],
            'DB_PASSWORD'         => $data['db_password'] ?? '',
            'MAIL_MAILER'         => $data['mail_mailer'],
            'MAIL_HOST'           => $data['mail_host'] ?? '',
            'MAIL_PORT'           => $data['mail_port'] ?? '587',
            'MAIL_USERNAME'       => $data['mail_username'] ?? '',
            'MAIL_PASSWORD'       => $data['mail_password'] ?? '',
            'MAIL_FROM_ADDRESS'   => '"' . ($data['mail_from_address'] ?? '') . '"',
            'MAIL_FROM_NAME'      => '"' . ($data['mail_from_name'] ?? $data['app_name']) . '"',
            'SESSION_DRIVER'      => 'database',
            'CACHE_STORE'         => 'database',
            'QUEUE_CONNECTION'    => 'database',
        ];

        foreach ($map as $key => $value) {
            // Hapus komentar pada baris yang relevan
            $template = preg_replace('/^#\s*(' . preg_quote($key) . '=.*)$/m', '$1', $template);

            if (preg_match('/^' . preg_quote($key) . '=/m', $template)) {
                // Ganti nilai yang sudah ada
                $template = preg_replace('/^' . preg_quote($key) . '=.*/m', $key . '=' . $value, $template);
            } else {
                // Tambahkan di akhir jika belum ada
                $template .= "\n" . $key . '=' . $value;
            }
        }

        // Pastikan APP_KEY ada (kosong dulu, akan di-generate di langkah berikutnya)
        if (!preg_match('/^APP_KEY=/m', $template)) {
            $template .= "\nAPP_KEY=";
        }

        try {
            file_put_contents($envPath, $template);
            return response()->json(['success' => true, 'message' => 'File .env berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menulis .env: ' . $e->getMessage()]);
        }
    }

    // -------------------------------------------------------------------------
    // POST /setup/generate-key  →  Generate APP_KEY
    // -------------------------------------------------------------------------
    public function generateKey(Request $request)
    {
        if (!$this->checkToken($request)) {
            return response()->json(['success' => false, 'message' => 'Tidak terautentikasi.'], 401);
        }

        try {
            // Reload config dari .env yang baru ditulis
            $this->reloadEnv();

            Artisan::call('key:generate', ['--force' => true]);
            $output = Artisan::output();

            return response()->json(['success' => true, 'message' => 'APP_KEY berhasil di-generate.', 'output' => trim($output)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal generate key: ' . $e->getMessage()]);
        }
    }

    // -------------------------------------------------------------------------
    // POST /setup/test-db  →  Uji koneksi database
    // -------------------------------------------------------------------------
    public function testDb(Request $request)
    {
        if (!$this->checkToken($request)) {
            return response()->json(['success' => false, 'message' => 'Tidak terautentikasi.'], 401);
        }

        try {
            $this->reloadEnv();
            // Paksa ulang konfigurasi database
            config([
                'database.connections.mysql.host'     => env('DB_HOST', '127.0.0.1'),
                'database.connections.mysql.port'     => env('DB_PORT', '3306'),
                'database.connections.mysql.database' => env('DB_DATABASE', ''),
                'database.connections.mysql.username' => env('DB_USERNAME', ''),
                'database.connections.mysql.password' => env('DB_PASSWORD', ''),
            ]);
            DB::purge('mysql');
            DB::reconnect('mysql');

            DB::connection('mysql')->getPdo();
            return response()->json(['success' => true, 'message' => 'Koneksi database berhasil!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Koneksi gagal: ' . $e->getMessage()]);
        }
    }

    // -------------------------------------------------------------------------
    // POST /setup/migrate  →  Jalankan migrasi
    // -------------------------------------------------------------------------
    public function migrate(Request $request)
    {
        if (!$this->checkToken($request)) {
            return response()->json(['success' => false, 'message' => 'Tidak terautentikasi.'], 401);
        }

        try {
            $this->reloadEnv();
            config([
                'database.connections.mysql.host'     => env('DB_HOST', '127.0.0.1'),
                'database.connections.mysql.port'     => env('DB_PORT', '3306'),
                'database.connections.mysql.database' => env('DB_DATABASE', ''),
                'database.connections.mysql.username' => env('DB_USERNAME', ''),
                'database.connections.mysql.password' => env('DB_PASSWORD', ''),
            ]);
            DB::purge('mysql');

            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            return response()->json(['success' => true, 'message' => 'Migrasi berhasil.', 'output' => trim($output)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Migrasi gagal: ' . $e->getMessage()]);
        }
    }

    // -------------------------------------------------------------------------
    // POST /setup/seed  →  Jalankan seeder
    // -------------------------------------------------------------------------
    public function seed(Request $request)
    {
        if (!$this->checkToken($request)) {
            return response()->json(['success' => false, 'message' => 'Tidak terautentikasi.'], 401);
        }

        try {
            $this->reloadEnv();
            Artisan::call('db:seed', ['--force' => true]);
            $output = Artisan::output();

            return response()->json(['success' => true, 'message' => 'Seeder berhasil dijalankan.', 'output' => trim($output)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Seeder gagal: ' . $e->getMessage()]);
        }
    }

    // -------------------------------------------------------------------------
    // POST /setup/finalize  →  Storage link + clear cache + selesai
    // -------------------------------------------------------------------------
    public function finalize(Request $request)
    {
        if (!$this->checkToken($request)) {
            return response()->json(['success' => false, 'message' => 'Tidak terautentikasi.'], 401);
        }

        $results = [];

        // Storage link
        try {
            // Hapus symlink lama jika ada
            $linkPath = public_path('storage');
            if (is_link($linkPath)) {
                unlink($linkPath);
            }
            Artisan::call('storage:link');
            $results[] = ['label' => 'Storage Link', 'success' => true, 'msg' => Artisan::output()];
        } catch (\Exception $e) {
            $results[] = ['label' => 'Storage Link', 'success' => false, 'msg' => $e->getMessage()];
        }

        // Cache & config clear
        $commands = [
            'Config Cache'  => fn() => Artisan::call('config:clear'),
            'Cache Clear'   => fn() => Artisan::call('cache:clear'),
            'View Clear'    => fn() => Artisan::call('view:clear'),
            'Route Clear'   => fn() => Artisan::call('route:clear'),
        ];
        foreach ($commands as $label => $cmd) {
            try {
                $cmd();
                $results[] = ['label' => $label, 'success' => true, 'msg' => trim(Artisan::output())];
            } catch (\Exception $e) {
                $results[] = ['label' => $label, 'success' => false, 'msg' => $e->getMessage()];
            }
        }

        // Tandai setup selesai
        file_put_contents($this->completedFlagPath(), date('Y-m-d H:i:s'));

        return response()->json(['success' => true, 'results' => $results, 'message' => 'Setup selesai!']);
    }

    // -------------------------------------------------------------------------
    // POST /setup/reset-lock  →  Hapus lock file (jika ingin setup ulang)
    // -------------------------------------------------------------------------
    public function resetLock(Request $request)
    {
        if ($request->input('token') !== self::SETUP_TOKEN) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid.'], 401);
        }
        if (file_exists($this->completedFlagPath())) {
            unlink($this->completedFlagPath());
        }
        $request->session()->forget('setup_authenticated');
        return response()->json(['success' => true, 'message' => 'Lock file dihapus. Setup bisa dijalankan ulang.']);
    }

    // -------------------------------------------------------------------------
    // Helper: Reload .env ke dalam environment PHP
    // -------------------------------------------------------------------------
    private function reloadEnv(): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }
            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $key   = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            if (!empty($key)) {
                putenv("$key=$value");
                $_ENV[$key]    = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}
