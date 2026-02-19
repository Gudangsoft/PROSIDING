<?php
/**
 * Storage Symlink Creator for cPanel
 * 
 * CARA PAKAI:
 * 1. Upload file ini ke folder public/ di server
 * 2. Buka browser ke: https://conference.appisi.or.id/storage-link.php
 * 3. HAPUS file ini setelah symlink berhasil dibuat!
 */

// ─── Keamanan: hapus/ganti token ini ────────────────────────────────────────
define('ACCESS_TOKEN', 'storage-link-appisi-2024');

$token  = $_GET['token'] ?? '';
$action = $_GET['action'] ?? '';

// Cek token
if ($token !== ACCESS_TOKEN) {
    http_response_code(403);
    echo '<h3 style="color:red">403 Forbidden – Token tidak valid.</h3>';
    echo '<p>Akses: <code>?token=storage-link-appisi-2024&action=create</code></p>';
    exit;
}

// Tentukan path
$publicStoragePath = __DIR__ . '/storage';
$storagePublicPath = dirname(__DIR__) . '/storage/app/public';

$info = [
    'public/storage (symlink target)' => $publicStoragePath,
    'storage/app/public (source)'     => $storagePublicPath,
    'Source exists'      => is_dir($storagePublicPath)  ? 'YES ✓' : 'NO ✗',
    'Symlink exists'     => is_link($publicStoragePath)  ? 'YES ✓' : 'NO',
    'Current points to'  => is_link($publicStoragePath)  ? readlink($publicStoragePath) : '-',
    'Real dir exists'    => is_dir($publicStoragePath)   ? 'YES (real dir, bukan symlink)' : 'NO',
];

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Storage Symlink Creator</title>
<style>
  body { font-family: monospace; background:#0f172a; color:#e2e8f0; padding:30px; }
  h2   { color:#38bdf8; }
  table { border-collapse:collapse; width:100%; max-width:700px; margin:16px 0; }
  td,th { padding:8px 12px; border:1px solid #334155; text-align:left; font-size:.9rem; }
  th    { background:#1e293b; color:#94a3b8; }
  .ok   { color:#4ade80; } .fail { color:#f87171; } .warn { color:#fbbf24; }
  .btn  { display:inline-block; margin-top:20px; padding:10px 24px; background:#3b82f6;
          color:#fff; text-decoration:none; border-radius:8px; font-size:1rem; }
  .btn:hover { background:#2563eb; }
  pre   { background:#1e293b; padding:12px; border-radius:6px; overflow-x:auto; }
</style>
</head>
<body>
<h2>⚙ Storage Symlink Creator</h2>

<h3>Informasi Path</h3>
<table>
  <tr><th>Item</th><th>Value</th></tr>
  <?php foreach ($info as $k => $v): ?>
  <tr><td><?= htmlspecialchars($k) ?></td>
      <td class="<?= str_contains($v,'YES') ? 'ok' : (str_contains($v,'NO') ? 'fail' : '') ?>">
          <?= htmlspecialchars($v) ?></td></tr>
  <?php endforeach; ?>
</table>

<?php if ($action === 'create'): ?>
<h3>Hasil Pembuatan Symlink</h3>
<?php
    $messages = [];

    // Pastikan source ada
    if (!is_dir($storagePublicPath)) {
        // Buat folder jika belum ada
        if (mkdir($storagePublicPath, 0775, true)) {
            $messages[] = ['ok', 'Folder storage/app/public dibuat.'];
        } else {
            $messages[] = ['fail', 'Gagal membuat folder storage/app/public.'];
        }
    }

    // Hapus symlink/dir lama di public/storage jika ada
    if (is_link($publicStoragePath)) {
        unlink($publicStoragePath);
        $messages[] = ['warn', 'Symlink lama dihapus.'];
    } elseif (is_dir($publicStoragePath)) {
        $messages[] = ['warn', 'Ada folder real di public/storage (bukan symlink). Tidak dihapus otomatis – hapus manual via File Manager jika perlu.'];
    }

    // Buat symlink
    if (!is_dir($publicStoragePath) && !is_link($publicStoragePath)) {
        if (symlink($storagePublicPath, $publicStoragePath)) {
            $messages[] = ['ok', 'Symlink berhasil dibuat: public/storage → storage/app/public'];
        } else {
            // Fallback: coba bikin folder dan .htaccess redirect
            $messages[] = ['fail', 'symlink() gagal (server mungkin tidak izinkan symlink).'];
            $messages[] = ['warn', 'Mencoba metode alternatif: folder + .htaccess…'];

            if (mkdir($publicStoragePath, 0775, true)) {
                $htaccess = "Options -Indexes\n";
                file_put_contents($publicStoragePath . '/.htaccess', $htaccess);

                // Buat PHP redirect script
                $indexPhp = '<?php' . "\n" .
                    '$file = str_replace("' . $publicStoragePath . '", "' . $storagePublicPath . '", $_SERVER["SCRIPT_FILENAME"]);' . "\n" .
                    'if(file_exists($file)){ header("Content-Type: " . mime_content_type($file)); readfile($file); exit; }' . "\n" .
                    'http_response_code(404); echo "File not found.";';
                file_put_contents($publicStoragePath . '/index.php', $indexPhp);

                $messages[] = ['warn', 'Folder public/storage dibuat. Tapi symlink tidak bisa dibuat – file mungkin tidak bisa diakses langsung.'];
                $messages[] = ['warn', 'Solusi: di cPanel File Manager, klik kanan folder public/storage → "Create Symlink" ke ../storage/app/public'];
            }
        }
    } elseif (is_link($publicStoragePath)) {
        $messages[] = ['ok', 'Symlink sudah ada dan aktif.'];
    }

    // Verifikasi
    if (is_link($publicStoragePath)) {
        $messages[] = ['ok', '✓ Verifikasi: ' . $publicStoragePath . ' → ' . readlink($publicStoragePath)];
    }

    foreach ($messages as [$cls, $msg]):
?>
<p class="<?= $cls ?>">
    <?= $cls === 'ok' ? '✓' : ($cls === 'fail' ? '✗' : '⚠') ?>
    <?= htmlspecialchars($msg) ?>
</p>
<?php endforeach; ?>

<a class="btn" href="?token=<?= ACCESS_TOKEN ?>">← Lihat Status Terbaru</a>

<?php else: ?>

<a class="btn" href="?token=<?= ACCESS_TOKEN ?>&action=create">▶ Buat Symlink Sekarang</a>

<?php endif; ?>

<hr style="border-color:#334155;margin:30px 0">
<p class="warn">⚠ <strong>PENTING:</strong> Hapus file <code>public/storage-link.php</code> ini setelah selesai!</p>
</body>
</html>
