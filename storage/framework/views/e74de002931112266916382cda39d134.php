<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Setup Installer – Prosiding</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f0f4f8; font-family: 'Segoe UI', sans-serif; }
        .setup-card { max-width: 760px; margin: 40px auto; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,.12); }
        .setup-header { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: #fff; border-radius: 16px 16px 0 0; padding: 28px 32px; }
        .step-bar { display: flex; gap: 6px; margin-top: 18px; }
        .step-bar .step { flex: 1; height: 6px; border-radius: 4px; background: rgba(255,255,255,.35); transition: background .3s; }
        .step-bar .step.active { background: #fff; }
        .step-bar .step.done   { background: #86efac; }
        .setup-body { background: #fff; border-radius: 0 0 16px 16px; padding: 32px; }
        .log-box { background: #0f172a; color: #86efac; font-family: 'Courier New', monospace; font-size: .82rem; padding: 14px; border-radius: 8px; max-height: 220px; overflow-y: auto; white-space: pre-wrap; }
        .check-item { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: .92rem; }
        .check-item:last-child { border: none; }
        .badge-pass   { background: #dcfce7; color: #166534; padding: 2px 10px; border-radius: 12px; font-size: .78rem; }
        .badge-fail   { background: #fee2e2; color: #991b1b; padding: 2px 10px; border-radius: 12px; font-size: .78rem; }
        .spinner-sm   { width: 18px; height: 18px; border-width: 2px; }
        .section-title { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #64748b; margin-bottom: 12px; margin-top: 20px; }
    </style>
</head>
<body>

<div class="setup-card card border-0">

    
    <div class="setup-header">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-gear-fill fs-3"></i>
            <div>
                <h4 class="mb-0 fw-bold">Setup Installer</h4>
                <small class="opacity-75">Deploy Laravel ke cPanel tanpa terminal</small>
            </div>
        </div>
        <div class="step-bar" id="stepBar">
            <div class="step" id="bar0"></div>
            <div class="step" id="bar1"></div>
            <div class="step" id="bar2"></div>
            <div class="step" id="bar3"></div>
            <div class="step" id="bar4"></div>
        </div>
    </div>

    
    <div class="setup-body">

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($completed): ?>
        <div class="text-center py-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size:4rem"></i>
            <h4 class="mt-3 fw-bold">Setup Sudah Selesai</h4>
            <p class="text-muted">Aplikasi sudah dikonfigurasi. Halaman setup dinonaktifkan untuk keamanan.</p>
            <a href="<?php echo e(url('/')); ?>" class="btn btn-primary mt-2">Ke Beranda &rarr;</a>
            <hr class="my-4">
            <p class="text-muted small">Jika ingin setup ulang, hapus file <code>storage/app/setup_complete.lock</code> via cPanel File Manager.</p>
        </div>
        <?php else: ?>

        
        <div id="step0">
            <h5 class="fw-bold mb-1">Selamat Datang</h5>
            <p class="text-muted small mb-4">Masukkan token keamanan untuk melanjutkan setup. Token ini ada di <code>SetupController.php</code> konstanta <code>SETUP_TOKEN</code>.</p>
            <div class="mb-3">
                <label class="form-label fw-semibold">Token Akses</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                    <input type="password" id="inputToken" class="form-control" placeholder="Masukkan setup token…">
                </div>
            </div>
            <div id="authError" class="alert alert-danger d-none small py-2"></div>
            <button class="btn btn-primary w-100" onclick="doAuth()">
                <span id="authSpinner" class="spinner-border spinner-sm d-none me-2"></span>
                Lanjutkan <i class="bi bi-arrow-right-short"></i>
            </button>
        </div>

        
        <div id="step1" class="d-none">
            <h5 class="fw-bold mb-1">Cek Kebutuhan Sistem</h5>
            <p class="text-muted small mb-3">Memeriksa PHP versi, ekstensi, dan izin folder yang diperlukan Laravel.</p>
            <div id="reqList">
                <div class="text-center py-4 text-muted">
                    <div class="spinner-border text-primary spinner-sm mb-2"></div>
                    <div class="small">Memeriksa…</div>
                </div>
            </div>
            <div id="reqError" class="alert alert-danger d-none small py-2 mt-3"></div>
            <button id="btnReqNext" class="btn btn-primary w-100 mt-3 d-none" onclick="goStep(2)">
                Lanjut ke Konfigurasi <i class="bi bi-arrow-right-short"></i>
            </button>
        </div>

        
        <div id="step2" class="d-none">
            <h5 class="fw-bold mb-1">Konfigurasi Aplikasi</h5>
            <p class="text-muted small mb-3">Isi pengaturan di bawah. Data ini akan disimpan ke file <code>.env</code>.</p>

            <div class="section-title"><i class="bi bi-app-indicator me-1"></i>Informasi Aplikasi</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nama Aplikasi</label>
                    <input type="text" id="app_name" class="form-control" value="PROSIDING APJI">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">URL Aplikasi</label>
                    <input type="text" id="app_url" class="form-control" placeholder="https://domain-anda.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Environment</label>
                    <select id="app_env" class="form-select">
                        <option value="production" selected>production</option>
                        <option value="local">local</option>
                        <option value="staging">staging</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Debug Mode</label>
                    <select id="app_debug" class="form-select">
                        <option value="false" selected>false (matikan di production)</option>
                        <option value="true">true</option>
                    </select>
                </div>
            </div>

            <div class="section-title mt-4"><i class="bi bi-database me-1"></i>Database (MySQL)</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">DB Host</label>
                    <input type="text" id="db_host" class="form-control" value="localhost">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">DB Port</label>
                    <input type="text" id="db_port" class="form-control" value="3306">
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-semibold">Nama Database</label>
                    <input type="text" id="db_database" class="form-control" placeholder="nama_database_cpanel">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Username Database</label>
                    <input type="text" id="db_username" class="form-control" placeholder="user_db">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Password Database</label>
                    <input type="password" id="db_password" class="form-control" placeholder="(kosongkan jika tidak ada)">
                </div>
            </div>

            <div class="section-title mt-4"><i class="bi bi-envelope me-1"></i>Email / SMTP</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Mail Driver</label>
                    <select id="mail_mailer" class="form-select">
                        <option value="smtp">smtp</option>
                        <option value="sendmail">sendmail</option>
                        <option value="log" selected>log (hanya catat, tidak kirim)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">SMTP Host</label>
                    <input type="text" id="mail_host" class="form-control" placeholder="mail.domain.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">SMTP Port</label>
                    <input type="text" id="mail_port" class="form-control" value="465">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">SMTP Username</label>
                    <input type="text" id="mail_username" class="form-control" placeholder="email@domain.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">SMTP Password</label>
                    <input type="password" id="mail_password" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">From Email</label>
                    <input type="email" id="mail_from_address" class="form-control" placeholder="noreply@domain.com">
                </div>
            </div>

            <div id="envError" class="alert alert-danger d-none small py-2 mt-3"></div>
            <button class="btn btn-primary w-100 mt-4" onclick="doSaveEnv()">
                <span id="envSpinner" class="spinner-border spinner-sm d-none me-2"></span>
                <i class="bi bi-save me-1"></i>Simpan &amp; Lanjutkan
            </button>
        </div>

        
        <div id="step3" class="d-none">
            <h5 class="fw-bold mb-1">Setup Database</h5>
            <p class="text-muted small mb-4">Uji koneksi database, jalankan migrasi tabel, dan (opsional) seeder data awal.</p>

            
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold small">1. Test Koneksi Database</div>
                        <div class="text-muted" style="font-size:.8rem">Cek apakah kredensial database valid</div>
                    </div>
                    <button class="btn btn-outline-primary btn-sm" id="btnTestDb" onclick="doTestDb()">
                        <span id="testDbSpinner" class="spinner-border spinner-sm d-none me-1"></span>
                        Test
                    </button>
                </div>
                <div id="testDbResult" class="mt-2 small d-none"></div>
            </div>

            
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold small">2. Jalankan Migrasi</div>
                        <div class="text-muted" style="font-size:.8rem">Buat semua tabel database</div>
                    </div>
                    <button class="btn btn-outline-warning btn-sm" id="btnMigrate" onclick="doMigrate()">
                        <span id="migrateSpinner" class="spinner-border spinner-sm d-none me-1"></span>
                        Migrate
                    </button>
                </div>
                <div id="migrateResult" class="mt-2 small d-none"></div>
            </div>

            
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold small">3. Jalankan Seeder <span class="badge bg-secondary" style="font-size:.65rem">Opsional</span></div>
                        <div class="text-muted" style="font-size:.8rem">Isi data awal (role, setting, admin, dll). Lewati jika belum perlu.</div>
                    </div>
                    <button class="btn btn-outline-success btn-sm" id="btnSeed" onclick="doSeed()">
                        <span id="seedSpinner" class="spinner-border spinner-sm d-none me-1"></span>
                        Seed
                    </button>
                </div>
                <div id="seedResult" class="mt-2 small d-none"></div>
            </div>

            <div id="dbError" class="alert alert-danger d-none small py-2"></div>
            <button id="btnDbNext" class="btn btn-primary w-100 mt-1" onclick="goStep(4)">
                Lanjut ke Finalisasi <i class="bi bi-arrow-right-short"></i>
            </button>
        </div>

        
        <div id="step4" class="d-none">
            <h5 class="fw-bold mb-1">Finalisasi</h5>
            <p class="text-muted small mb-4">Buat storage symlink, generate APP_KEY, dan bersihkan cache. Klik tombol di bawah untuk menyelesaikan.</p>

            <div id="finalizeResult" class="d-none"></div>

            <button class="btn btn-success w-100" id="btnFinalize" onclick="doFinalize()">
                <span id="finalizeSpinner" class="spinner-border spinner-sm d-none me-2"></span>
                <i class="bi bi-rocket-takeoff me-1"></i>Selesaikan Setup
            </button>
        </div>

        
        <div id="step5" class="d-none text-center py-3">
            <i class="bi bi-check-circle-fill text-success" style="font-size:4rem"></i>
            <h4 class="mt-3 fw-bold text-success">Setup Berhasil!</h4>
            <p class="text-muted">Aplikasi sudah siap digunakan. Halaman setup telah dikunci otomatis.</p>
            <a href="<?php echo e(url('/')); ?>" class="btn btn-primary mt-2 px-5">
                <i class="bi bi-house me-1"></i>Buka Aplikasi
            </a>
            <div class="alert alert-warning mt-4 small text-start">
                <i class="bi bi-shield-exclamation me-1"></i>
                <strong>Keamanan:</strong> Setup sudah dikunci dengan file <code>storage/app/setup_complete.lock</code>.
                Halaman ini tidak bisa diakses lagi kecuali lock file dihapus.
            </div>
        </div>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<p class="text-center text-muted small mt-3 mb-5">
    Laravel <?php echo e(app()->version()); ?> &bull; PHP <?php echo e(PHP_VERSION); ?>

</p>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let currentStep = 0;

/* ─── Navigasi antar step ─── */
function goStep(n) {
    document.querySelectorAll('[id^="step"]').forEach(el => el.classList.add('d-none'));
    document.getElementById('step' + n).classList.remove('d-none');
    currentStep = n;
    updateBar();
}

function updateBar() {
    for (let i = 0; i < 5; i++) {
        const el = document.getElementById('bar' + i);
        if (i < currentStep)      el.className = 'step done';
        else if (i === currentStep) el.className = 'step active';
        else                       el.className = 'step';
    }
}

/* ─── Utility ─── */
async function post(url, body = {}) {
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify(body),
    });
    return res.json();
}

function setSpinner(id, show) {
    document.getElementById(id).classList.toggle('d-none', !show);
}

function showError(id, msg) {
    const el = document.getElementById(id);
    el.textContent = msg;
    el.classList.remove('d-none');
}

function hideError(id) {
    document.getElementById(id).classList.add('d-none');
}

function resultHtml(ok, msg) {
    return ok
        ? `<div class="alert alert-success py-2 mb-0 small"><i class="bi bi-check-circle me-1"></i>${msg}</div>`
        : `<div class="alert alert-danger py-2 mb-0 small"><i class="bi bi-x-circle me-1"></i>${msg}</div>`;
}

/* ─── STEP 0: Auth ─── */
async function doAuth() {
    hideError('authError');
    setSpinner('authSpinner', true);
    const token = document.getElementById('inputToken').value;
    const data  = await post('<?php echo e(route("setup.auth")); ?>', { token });
    setSpinner('authSpinner', false);
    if (data.success) {
        goStep(1);
        loadRequirements();
    } else {
        showError('authError', data.message || 'Token salah.');
    }
}
document.getElementById('inputToken')?.addEventListener('keydown', e => { if (e.key === 'Enter') doAuth(); });

/* ─── STEP 1: Requirements ─── */
async function loadRequirements() {
    const data = await post('<?php echo e(route("setup.requirements")); ?>');
    const list = document.getElementById('reqList');
    if (!data.success) {
        list.innerHTML = `<div class="alert alert-danger small">${data.message}</div>`;
        return;
    }
    let html = '';
    data.checks.forEach(c => {
        html += `<div class="check-item">
            <i class="bi bi-${c.pass ? 'check-circle-fill text-success' : 'x-circle-fill text-danger'} fs-5"></i>
            <span class="flex-grow-1">${c.label}</span>
            <span class="${c.pass ? 'badge-pass' : 'badge-fail'}">${c.pass ? 'OK' : 'GAGAL'}</span>
        </div>`;
    });
    list.innerHTML = html;

    if (data.allPass) {
        document.getElementById('btnReqNext').classList.remove('d-none');
    } else {
        document.getElementById('reqError').textContent = 'Beberapa persyaratan tidak terpenuhi. Perbaiki dulu sebelum melanjutkan.';
        document.getElementById('reqError').classList.remove('d-none');
        // Tetap izinkan lanjut dengan peringatan
        document.getElementById('btnReqNext').classList.remove('d-none');
        document.getElementById('btnReqNext').classList.replace('btn-primary', 'btn-warning');
        document.getElementById('btnReqNext').innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Lanjut dengan Peringatan';
    }
}

/* ─── STEP 2: Save ENV ─── */
async function doSaveEnv() {
    hideError('envError');
    setSpinner('envSpinner', true);

    const body = {
        app_name:    document.getElementById('app_name').value,
        app_url:     document.getElementById('app_url').value,
        app_env:     document.getElementById('app_env').value,
        app_debug:   document.getElementById('app_debug').value,
        db_host:     document.getElementById('db_host').value,
        db_port:     document.getElementById('db_port').value,
        db_database: document.getElementById('db_database').value,
        db_username: document.getElementById('db_username').value,
        db_password: document.getElementById('db_password').value,
        mail_mailer: document.getElementById('mail_mailer').value,
        mail_host:   document.getElementById('mail_host').value,
        mail_port:   document.getElementById('mail_port').value,
        mail_username: document.getElementById('mail_username').value,
        mail_password: document.getElementById('mail_password').value,
        mail_from_address: document.getElementById('mail_from_address').value,
        mail_from_name: document.getElementById('app_name').value,
    };

    const data = await post('<?php echo e(route("setup.save-env")); ?>', body);
    setSpinner('envSpinner', false);

    if (data.success) {
        // Generate key setelah save env
        const keyData = await post('<?php echo e(route("setup.generate-key")); ?>');
        if (!keyData.success) {
            showError('envError', 'Env tersimpan, tapi gagal generate key: ' + keyData.message);
            return;
        }
        goStep(3);
    } else {
        showError('envError', data.message || 'Gagal menyimpan .env');
    }
}

/* ─── STEP 3: DB ─── */
async function doTestDb() {
    setSpinner('testDbSpinner', true);
    document.getElementById('btnTestDb').disabled = true;
    const data = await post('<?php echo e(route("setup.test-db")); ?>');
    setSpinner('testDbSpinner', false);
    document.getElementById('btnTestDb').disabled = false;
    const el = document.getElementById('testDbResult');
    el.innerHTML = resultHtml(data.success, data.message);
    el.classList.remove('d-none');
}

async function doMigrate() {
    setSpinner('migrateSpinner', true);
    document.getElementById('btnMigrate').disabled = true;
    const data = await post('<?php echo e(route("setup.migrate")); ?>');
    setSpinner('migrateSpinner', false);
    document.getElementById('btnMigrate').disabled = false;
    const el = document.getElementById('migrateResult');
    const msg = data.message + (data.output ? '<br><pre class="mt-1 mb-0" style="font-size:.75rem">' + data.output + '</pre>' : '');
    el.innerHTML = resultHtml(data.success, msg);
    el.classList.remove('d-none');
}

async function doSeed() {
    if (!confirm('Jalankan seeder? Ini akan mengisi data awal ke database.')) return;
    setSpinner('seedSpinner', true);
    document.getElementById('btnSeed').disabled = true;
    const data = await post('<?php echo e(route("setup.seed")); ?>');
    setSpinner('seedSpinner', false);
    document.getElementById('btnSeed').disabled = false;
    const el = document.getElementById('seedResult');
    const msg = data.message + (data.output ? '<br><pre class="mt-1 mb-0" style="font-size:.75rem">' + data.output + '</pre>' : '');
    el.innerHTML = resultHtml(data.success, msg);
    el.classList.remove('d-none');
}

/* ─── STEP 4: Finalize ─── */
async function doFinalize() {
    setSpinner('finalizeSpinner', true);
    document.getElementById('btnFinalize').disabled = true;
    const data = await post('<?php echo e(route("setup.finalize")); ?>');
    setSpinner('finalizeSpinner', false);

    if (data.success) {
        let html = '<div class="mb-3">';
        data.results.forEach(r => {
            html += resultHtml(r.success, `<strong>${r.label}:</strong> ${r.msg || (r.success ? 'Berhasil' : 'Gagal')}`);
        });
        html += '</div>';
        const el = document.getElementById('finalizeResult');
        el.innerHTML = html;
        el.classList.remove('d-none');
        setTimeout(() => goStep(5), 1200);
    } else {
        document.getElementById('btnFinalize').disabled = false;
        alert('Gagal: ' + data.message);
    }
}

/* Init */
updateBar();
</script>
</body>
</html>
<?php /**PATH D:\LPKD-APJI\PROSIDING\resources\views/setup/index.blade.php ENDPATH**/ ?>