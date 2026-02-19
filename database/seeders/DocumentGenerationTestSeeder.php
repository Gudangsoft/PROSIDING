<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Paper;
use App\Models\PaperFile;
use App\Models\Conference;

class DocumentGenerationTestSeeder extends Seeder
{
    /**
     * Seeder untuk menguji fitur Auto-Generate LOA & Certificate.
     *
     * Jalankan dengan:
     *   php artisan db:seed --class=DocumentGenerationTestSeeder
     *
     * Akun yang dibuat:
     *   - Admin  : admin@prosiding.test / password
     *   - Author : dummy.author@prosiding.test / password
     */
    public function run(): void
    {
        // ─── 1. Pastikan ada akun admin ─────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@prosiding.test'],
            [
                'name'        => 'Admin Prosiding',
                'password'    => bcrypt('password'),
                'role'        => 'admin',
                'institution' => 'Panitia Prosiding',
                'phone'       => '081200000000',
            ]
        );

        // ─── 2. Buat author dummy ────────────────────────────────────────────
        $author = User::firstOrCreate(
            ['email' => 'dummy.author@prosiding.test'],
            [
                'name'        => 'Budi Santoso',
                'password'    => bcrypt('password'),
                'role'        => 'author',
                'institution' => 'Universitas Nusantara',
                'phone'       => '082100000001',
            ]
        );

        // ─── 3. Buat / ambil konferensi aktif dengan mode AUTO ───────────────
        $conference = Conference::where('is_active', true)->first();

        if ($conference) {
            // Update mode ke auto agar bisa ditest
            $conference->update([
                'loa_generation_mode'         => 'auto',
                'certificate_generation_mode' => 'auto',
            ]);
            $this->command->info("♻️  Konferensi aktif diupdate → LOA & Certificate mode: AUTO");
        } else {
            $conference = Conference::create([
                'name'                        => 'Seminar Nasional Teknologi Informasi 2026',
                'acronym'                     => 'SNTI',
                'theme'                       => 'Transformasi Digital untuk Indonesia Emas',
                'description'                 => 'Konferensi dummy untuk pengujian fitur auto-generate LOA & Certificate.',
                'start_date'                  => now()->addDays(30),
                'end_date'                    => now()->addDays(32),
                'venue'                       => 'Gedung Graha Sabha Pramana, Yogyakarta',
                'venue_type'                  => 'hybrid',
                'city'                        => 'Yogyakarta',
                'organizer'                   => 'Universitas Nusantara',
                'status'                      => 'published',
                'is_active'                   => true,
                'created_by'                  => $admin->id,
                'loa_generation_mode'         => 'auto',
                'certificate_generation_mode' => 'auto',
            ]);
            $this->command->info("🆕 Konferensi baru dibuat: {$conference->name}");
        }

        // ─── 4. Buat paper dummy dengan status accepted ──────────────────────
        $papers = [
            [
                'title'    => 'Implementasi Deep Learning untuk Deteksi Penyakit Tanaman Padi',
                'abstract' => 'Penelitian ini mengusulkan metode deteksi dini penyakit tanaman padi menggunakan convolutional neural network (CNN) dengan akurasi 94,7%. Dataset terdiri dari 5.000 gambar daun padi yang dikumpulkan dari 10 kabupaten di Jawa Barat.',
                'keywords' => 'deep learning, CNN, penyakit tanaman, pertanian cerdas',
                'topic'    => 'Kecerdasan Buatan',
                'status'   => 'accepted',
            ],
            [
                'title'    => 'Sistem Rekomendasi Wisata Berbasis Collaborative Filtering di Era Post-Pandemi',
                'abstract' => 'Makalah ini membahas pengembangan sistem rekomendasi destinasi wisata menggunakan algoritma collaborative filtering yang diperkaya data sentimen media sosial untuk meningkatkan kepuasan wisatawan.',
                'keywords' => 'collaborative filtering, sistem rekomendasi, pariwisata, NLP',
                'topic'    => 'Sistem Informasi',
                'status'   => 'accepted',
            ],
            [
                'title'    => 'Keamanan Data pada Platform Cloud Hybrid: Analisis Risiko dan Mitigasi',
                'abstract' => 'Studi komprehensif mengenai potensi risiko keamanan data pada implementasi cloud hybrid di lingkungan enterprise, beserta strategi mitigasi yang telah terbukti efektif.',
                'keywords' => 'cloud computing, keamanan data, manajemen risiko, enterprise',
                'topic'    => 'Keamanan Siber',
                'status'   => 'accepted',
            ],
        ];

        $created = [];
        foreach ($papers as $data) {
            $paper = Paper::create([
                'user_id'      => $author->id,
                'conference_id'=> $conference->id,
                'title'        => $data['title'],
                'abstract'     => $data['abstract'],
                'keywords'     => $data['keywords'],
                'topic'        => $data['topic'],
                'status'       => $data['status'],
                'submitted_at' => now()->subDays(14),
                'accepted_at'  => now()->subDays(3),
            ]);

            // Dummy paper file entry (path tidak harus ada secara fisik untuk test PDF-generate)
            PaperFile::create([
                'paper_id'      => $paper->id,
                'type'          => 'full_paper',
                'file_path'     => 'papers/dummy/full_paper_' . $paper->id . '.pdf',
                'original_name' => 'full_paper_' . $paper->id . '.pdf',
                'mime_type'     => 'application/pdf',
                'file_size'     => rand(400000, 1800000),
            ]);

            $created[] = $paper;
        }

        // ─── 5. Ringkasan ────────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('✅ DocumentGenerationTestSeeder selesai!');
        $this->command->newLine();
        $this->command->line('═══════════════════════════════════════════════');
        $this->command->line('  AKUN LOGIN');
        $this->command->line('═══════════════════════════════════════════════');
        $this->command->line("  Admin  : admin@prosiding.test  / password");
        $this->command->line("  Author : dummy.author@prosiding.test / password");
        $this->command->newLine();
        $this->command->line('  KONFERENSI AKTIF');
        $this->command->line('═══════════════════════════════════════════════');
        $this->command->line("  {$conference->name} (ID: {$conference->id})");
        $this->command->line("  LOA Mode         : {$conference->loa_generation_mode}");
        $this->command->line("  Certificate Mode : {$conference->certificate_generation_mode}");
        $this->command->newLine();
        $this->command->line('  PAPER DUMMY (status: accepted)');
        $this->command->line('═══════════════════════════════════════════════');
        foreach ($created as $p) {
            $this->command->line("  ID {$p->id}: {$p->title}");
        }
        $this->command->newLine();
        $this->command->line('  CARA TEST');
        $this->command->line('═══════════════════════════════════════════════');
        $this->command->line('  1. Login sebagai admin');
        $this->command->line('  2. Buka halaman Paper Management');
        $this->command->line('  3. Klik "Generate All Certificates" (tombol ungu)');
        $this->command->line('     ATAU buka salah satu paper → Accept → pilih Auto-Generate LOA');
        $this->command->line('  4. File PDF tersimpan di: storage/app/public/loa/ dan certificates/');
        $this->command->line('     Akses via: php artisan storage:link (jika belum)');
        $this->command->line('═══════════════════════════════════════════════');
        $this->command->newLine();
    }
}
