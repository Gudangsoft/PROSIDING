<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Conference;
use App\Models\ImportantDate;
use App\Models\Committee;
use App\Models\Topic;
use App\Models\KeynoteSpeaker;
use App\Models\RegistrationPackage;
use App\Models\SubmissionGuideline;
use App\Models\Paper;
use App\Models\PaperFile;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create dummy PDF files for seeder
        $this->createDummyPdfFiles();

        // Seed settings (email, theme, general)
        $this->call(SettingsSeeder::class);

        // Admin
        User::create([
            'name' => 'Admin Prosiding',
            'email' => 'admin@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'institution' => 'LPKD APJI',
            'phone' => '081234567890',
        ]);

        // Editor
        User::create([
            'name' => 'Editor Prosiding',
            'email' => 'editor@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'editor',
            'institution' => 'LPKD APJI',
            'phone' => '081234567891',
        ]);

        // Reviewer
        User::create([
            'name' => 'Dr. Reviewer',
            'email' => 'reviewer@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'institution' => 'Universitas Indonesia',
            'phone' => '081234567892',
        ]);

        // Reviewer 2
        User::create([
            'name' => 'Dr. Reviewer Dua',
            'email' => 'reviewer2@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'institution' => 'Institut Teknologi Bandung',
            'phone' => '081234567893',
        ]);

        // Reviewer 3
        User::create([
            'name' => 'Prof. Dr. Andi Wijaya, M.Pd.',
            'email' => 'reviewer3@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'institution' => 'Universitas Negeri Yogyakarta',
            'phone' => '081234567900',
        ]);

        // Reviewer 4
        User::create([
            'name' => 'Dr. Siti Nurhaliza, M.T.',
            'email' => 'reviewer4@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'institution' => 'Universitas Pendidikan Indonesia',
            'phone' => '081234567901',
        ]);

        // Reviewer 5
        User::create([
            'name' => 'Dr. Bambang Susanto, M.Sc.',
            'email' => 'reviewer5@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'institution' => 'Institut Teknologi Sepuluh Nopember',
            'phone' => '081234567902',
        ]);

        // Reviewer 6
        User::create([
            'name' => 'Prof. Dr. Ratna Dewi, M.Pd.',
            'email' => 'reviewer6@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'institution' => 'Universitas Negeri Malang',
            'phone' => '081234567903',
        ]);

        // Reviewer 7
        User::create([
            'name' => 'Dr. Hendra Gunawan, M.T.',
            'email' => 'reviewer7@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'institution' => 'Universitas Gadjah Mada',
            'phone' => '081234567904',
        ]);

        // Reviewer 8
        User::create([
            'name' => 'Dr. Mega Puspita, M.Pd.',
            'email' => 'reviewer8@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'institution' => 'Universitas Negeri Semarang',
            'phone' => '081234567905',
        ]);

        // Reviewer 9
        User::create([
            'name' => 'Prof. Dr. Agus Prasetyo, M.Si.',
            'email' => 'reviewer9@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'institution' => 'Universitas Airlangga',
            'phone' => '081234567906',
        ]);

        // Reviewer 10
        User::create([
            'name' => 'Dr. Dewi Kartika, M.Eng.',
            'email' => 'reviewer10@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'institution' => 'Universitas Diponegoro',
            'phone' => '081234567907',
        ]);

        // Author
        User::create([
            'name' => 'Penulis Satu',
            'email' => 'author@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'author',
            'institution' => 'Universitas Gadjah Mada',
            'phone' => '081234567894',
        ]);

        // Author 2
        User::create([
            'name' => 'Penulis Dua',
            'email' => 'author2@prosiding.test',
            'password' => Hash::make('password'),
            'role' => 'author',
            'institution' => 'Universitas Brawijaya',
            'phone' => '081234567895',
        ]);

        // ====================================================
        // CONTOH KEGIATAN LENGKAP 2026
        // ====================================================
        $admin = User::where('email', 'admin@prosiding.test')->first();

        $conference = Conference::create([
            'name' => 'Lokakarya Pendidikan Kejuruan Daerah & Asosiasi Pendidikan Kejuruan Indonesia 2026',
            'acronym' => 'LPKD-APJI 2026',
            'theme' => 'Transformasi Pendidikan Kejuruan Menuju Era Digital dan Industri Berkelanjutan',
            'description' => 'Lokakarya Pendidikan Kejuruan Daerah (LPKD) bekerja sama dengan Asosiasi Pendidikan Kejuruan Indonesia (APJI) menyelenggarakan kegiatan ilmiah nasional tahun 2026 yang bertujuan untuk membahas inovasi, tantangan, dan peluang dalam pengembangan pendidikan kejuruan di Indonesia. Kegiatan ini menjadi wadah bagi akademisi, praktisi, dan pemangku kebijakan untuk berbagi ide dan penelitian terkini di bidang pendidikan kejuruan.',
            'start_date' => '2026-07-15',
            'end_date' => '2026-07-17',
            'venue' => 'Gedung Auditorium Universitas Negeri Yogyakarta',
            'city' => 'Yogyakarta',
            'organizer' => 'LPKD & Asosiasi Pendidikan Kejuruan Indonesia (APJI)',
            'status' => 'published',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // -- Important Dates --
        $importantDates = [
            ['title' => 'Batas Pengiriman Abstrak', 'date' => '2026-03-31', 'type' => 'submission_deadline', 'description' => 'Deadline pengiriman abstrak paper'],
            ['title' => 'Pemberitahuan Penerimaan Abstrak', 'date' => '2026-04-15', 'type' => 'notification', 'description' => 'Notifikasi hasil seleksi abstrak'],
            ['title' => 'Batas Pengiriman Full Paper', 'date' => '2026-05-15', 'type' => 'submission_deadline', 'description' => 'Deadline pengiriman full paper'],
            ['title' => 'Batas Review Paper', 'date' => '2026-06-01', 'type' => 'review_deadline', 'description' => 'Reviewer menyelesaikan review'],
            ['title' => 'Pemberitahuan Hasil Review', 'date' => '2026-06-10', 'type' => 'notification', 'description' => 'Notifikasi accepted/revision/rejected'],
            ['title' => 'Camera Ready Submission', 'date' => '2026-06-25', 'type' => 'camera_ready', 'description' => 'Batas pengiriman paper final'],
            ['title' => 'Early Bird Registration', 'date' => '2026-05-31', 'type' => 'early_bird', 'description' => 'Harga spesial pendaftaran awal'],
            ['title' => 'Batas Pendaftaran', 'date' => '2026-07-05', 'type' => 'registration_deadline', 'description' => 'Deadline pendaftaran peserta'],
            ['title' => 'Hari Pertama - Pembukaan & Keynote', 'date' => '2026-07-15', 'type' => 'conference_date', 'description' => 'Opening ceremony & keynote session'],
            ['title' => 'Hari Kedua - Presentasi Paralel', 'date' => '2026-07-16', 'type' => 'conference_date', 'description' => 'Presentasi paper paralel & workshop'],
            ['title' => 'Hari Ketiga - Penutupan', 'date' => '2026-07-17', 'type' => 'conference_date', 'description' => 'Panel diskusi & closing ceremony'],
        ];
        foreach ($importantDates as $i => $d) {
            ImportantDate::create(array_merge($d, ['conference_id' => $conference->id, 'sort_order' => $i]));
        }

        // -- Committees --
        $committees = [
            // Steering Committee
            ['name' => 'Prof. Dr. H. Suyanto, M.Pd.', 'title' => 'Prof. Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'type' => 'steering', 'role' => 'Ketua'],
            ['name' => 'Prof. Dr. Ir. Herminarto Sofyan, M.Pd.', 'title' => 'Prof. Dr. Ir.', 'institution' => 'Universitas Negeri Yogyakarta', 'type' => 'steering', 'role' => 'Anggota'],
            ['name' => 'Prof. Dr. Mochamad Bruri Triyono, M.Pd.', 'title' => 'Prof. Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'type' => 'steering', 'role' => 'Anggota'],
            // Organizing Committee
            ['name' => 'Dr. Putu Sudira, M.P.', 'title' => 'Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'type' => 'organizing', 'role' => 'Ketua Panitia'],
            ['name' => 'Dr. Istanto Wahyu Djatmiko, M.Pd.', 'title' => 'Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'type' => 'organizing', 'role' => 'Sekretaris'],
            ['name' => 'Dra. Sri Waluyanti, M.Pd.', 'title' => 'Dra.', 'institution' => 'Universitas Negeri Yogyakarta', 'type' => 'organizing', 'role' => 'Bendahara'],
            ['name' => 'Dr. Nurhening Yuniarti, M.T.', 'title' => 'Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'type' => 'organizing', 'role' => 'Koordinator Acara'],
            ['name' => 'Dr. Zamtinah, M.Pd.', 'title' => 'Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'type' => 'organizing', 'role' => 'Koordinator Publikasi'],
            // Scientific Committee
            ['name' => 'Prof. Dr. Amat Jaedun, M.Pd.', 'title' => 'Prof. Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'type' => 'scientific', 'role' => 'Ketua'],
            ['name' => 'Prof. Dr. Thomas Sukardi, M.Pd.', 'title' => 'Prof. Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'type' => 'scientific', 'role' => 'Anggota'],
            ['name' => 'Prof. Dr. Sudji Munadi, M.Pd.', 'title' => 'Prof. Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'type' => 'scientific', 'role' => 'Anggota'],
            ['name' => 'Dr. Widarto, M.Pd.', 'title' => 'Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'type' => 'scientific', 'role' => 'Anggota'],
        ];
        foreach ($committees as $i => $c) {
            Committee::create(array_merge($c, ['conference_id' => $conference->id, 'sort_order' => $i]));
        }

        // -- Topics --
        $topics = [
            ['code' => 'T01', 'name' => 'Kurikulum & Pembelajaran Kejuruan', 'description' => 'Inovasi kurikulum, model pembelajaran, dan asesmen di pendidikan kejuruan'],
            ['code' => 'T02', 'name' => 'Teknologi dan Media Pembelajaran', 'description' => 'Pemanfaatan teknologi digital, AI, VR/AR, dan media inovatif dalam pembelajaran kejuruan'],
            ['code' => 'T03', 'name' => 'Link and Match Industri', 'description' => 'Kerjasama industri, teaching factory, magang, dan sertifikasi kompetensi'],
            ['code' => 'T04', 'name' => 'Manajemen Pendidikan Kejuruan', 'description' => 'Tata kelola, kebijakan, akreditasi, dan penjaminan mutu pendidikan kejuruan'],
            ['code' => 'T05', 'name' => 'Pengembangan SDM & Kompetensi Guru', 'description' => 'Profesionalisme guru, pelatihan industri, dan pengembangan kapasitas pendidik kejuruan'],
            ['code' => 'T06', 'name' => 'Kewirausahaan & Ekonomi Kreatif', 'description' => 'Pendidikan kewirausahaan, startup, UMKM, dan ekonomi kreatif di lingkungan kejuruan'],
            ['code' => 'T07', 'name' => 'Pendidikan Vokasi & Politeknik', 'description' => 'Pengembangan program vokasi, diploma, dan pendidikan tinggi vokasional'],
            ['code' => 'T08', 'name' => 'Green Technology & Sustainability', 'description' => 'Teknologi ramah lingkungan, energi terbarukan, dan pembangunan berkelanjutan di pendidikan kejuruan'],
        ];
        foreach ($topics as $i => $t) {
            Topic::create(array_merge($t, ['conference_id' => $conference->id, 'sort_order' => $i]));
        }

        // -- Speakers --
        $speakers = [
            // Opening Speech
            ['type' => 'opening_speech', 'name' => 'Prof. Dr. H. Suyanto, M.Pd.', 'title' => 'Prof. Dr.', 'institution' => 'Rektor Universitas Negeri Yogyakarta', 'topic' => 'Sambutan Pembukaan & Arah Kebijakan Pendidikan Kejuruan', 'bio' => 'Rektor Universitas Negeri Yogyakarta yang memiliki visi mengembangkan universitas berwawasan kejuruan terkemuka di Asia Tenggara.'],
            ['type' => 'opening_speech', 'name' => 'Dr. Ir. H. Ahmad Fauzi, M.T.', 'title' => 'Dr. Ir.', 'institution' => 'Direktur Jenderal Pendidikan Vokasi, Kemendikbudristek', 'topic' => 'Kebijakan Nasional Pengembangan Pendidikan Vokasi Indonesia 2026', 'bio' => 'Direktur Jenderal Pendidikan Vokasi yang bertanggung jawab atas kebijakan dan pengembangan pendidikan vokasi nasional.'],

            // Keynote Speakers
            ['type' => 'keynote_speaker', 'name' => 'Prof. Dr. Ir. Nizam, M.Sc.', 'title' => 'Prof. Dr. Ir.', 'institution' => 'Universitas Gadjah Mada', 'topic' => 'Transformasi Digital dalam Pendidikan Kejuruan: Peluang dan Tantangan', 'bio' => 'Pakar teknologi pendidikan dan mantan Plt. Dirjen Dikti yang aktif dalam pengembangan literasi digital nasional.'],
            ['type' => 'keynote_speaker', 'name' => 'Prof. Dr. Badrun Kartowagiran, M.Pd.', 'title' => 'Prof. Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'topic' => 'Asesmen Kompetensi Berbasis Industri 4.0 di Pendidikan Kejuruan', 'bio' => 'Profesor di bidang evaluasi pendidikan dengan lebih dari 30 tahun pengalaman riset asesmen dan pengukuran pendidikan.'],
            ['type' => 'keynote_speaker', 'name' => 'Prof. Dato\' Dr. Ahmad Rizal Madar', 'title' => 'Prof. Dato\' Dr.', 'institution' => 'Universiti Tun Hussein Onn Malaysia', 'topic' => 'TVET Development in ASEAN: Best Practices and Future Directions', 'bio' => 'International expert in Technical and Vocational Education and Training (TVET) with extensive research across ASEAN countries.'],

            // Narasumber
            ['type' => 'narasumber', 'name' => 'Dr. Putu Sudira, M.P.', 'title' => 'Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'topic' => 'Model Teaching Factory dan Implementasinya di SMK', 'bio' => 'Pakar pendidikan kejuruan dengan fokus riset pada teaching factory, link and match industri, dan kurikulum SMK.'],
            ['type' => 'narasumber', 'name' => 'Dr. Endang Mulyatiningsih, M.Pd.', 'title' => 'Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'topic' => 'Penelitian Tindakan Kelas untuk Peningkatan Mutu Pembelajaran Kejuruan', 'bio' => 'Ahli metodologi penelitian pendidikan dengan spesialisasi penelitian tindakan dan R&D di bidang kejuruan.'],
            ['type' => 'narasumber', 'name' => 'Ir. Budi Hartono, M.M.', 'title' => 'Ir.', 'institution' => 'Kamar Dagang dan Industri (KADIN) DIY', 'topic' => 'Perspektif Industri: Kompetensi Lulusan SMK yang Dibutuhkan Dunia Kerja', 'bio' => 'Ketua KADIN DIY dan praktisi industri yang aktif dalam program link and match pendidikan kejuruan dengan industri.'],
            ['type' => 'narasumber', 'name' => 'Dr. Soenarto, M.Pd.', 'title' => 'Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'topic' => 'Sertifikasi Kompetensi dan Pengakuan Kualifikasi Lulusan Vokasi', 'bio' => 'Ahli pendidikan teknik elektro dengan fokus pada sertifikasi kompetensi dan standar kualifikasi nasional.'],

            // Moderator & Host
            ['type' => 'moderator_host', 'name' => 'Dr. Nurhening Yuniarti, M.T.', 'title' => 'Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'topic' => 'Moderator Sesi Keynote & Panel Diskusi', 'bio' => 'Dosen senior Pendidikan Teknik Elektro UNY yang berpengalaman memimpin forum ilmiah nasional dan internasional.'],
            ['type' => 'moderator_host', 'name' => 'Dr. Heri Retnawati, M.Pd.', 'title' => 'Dr.', 'institution' => 'Universitas Negeri Yogyakarta', 'topic' => 'Moderator Sesi Presentasi Paralel', 'bio' => 'Dosen Pascasarjana UNY dengan keahlian dalam pengukuran dan evaluasi pendidikan.'],
            ['type' => 'moderator_host', 'name' => 'Anisa Rahmawati, S.Pd., M.Pd.', 'title' => 'S.Pd., M.Pd.', 'institution' => 'Universitas Negeri Yogyakarta', 'topic' => 'Host / MC Acara', 'bio' => 'Dosen muda Fakultas Teknik UNY yang aktif sebagai pembawa acara kegiatan akademis.'],
        ];
        foreach ($speakers as $i => $s) {
            KeynoteSpeaker::create(array_merge($s, ['conference_id' => $conference->id, 'sort_order' => $i]));
        }

        // -- Registration Packages --
        $packages = [
            [
                'name' => 'Pemakalah Dosen/Peneliti',
                'price' => 500000,
                'description' => 'Untuk pemakalah dari kalangan dosen dan peneliti',
                'features' => ['Seminar Kit', 'Sertifikat Pemakalah', 'E-Prosiding ber-ISBN', 'Makan Siang 3 Hari', 'Coffee Break', 'Akses Seluruh Sesi'],
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Pemakalah Mahasiswa',
                'price' => 300000,
                'description' => 'Untuk pemakalah dari kalangan mahasiswa S1/S2/S3',
                'features' => ['Seminar Kit', 'Sertifikat Pemakalah', 'E-Prosiding ber-ISBN', 'Makan Siang 3 Hari', 'Coffee Break', 'Akses Seluruh Sesi'],
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Peserta Non-Pemakalah (Umum)',
                'price' => 250000,
                'description' => 'Untuk peserta umum tanpa presentasi paper',
                'features' => ['Seminar Kit', 'Sertifikat Peserta', 'Makan Siang 3 Hari', 'Coffee Break', 'Akses Seluruh Sesi'],
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Peserta Non-Pemakalah (Mahasiswa)',
                'price' => 150000,
                'description' => 'Untuk mahasiswa yang hadir sebagai peserta tanpa presentasi',
                'features' => ['Seminar Kit', 'Sertifikat Peserta', 'Makan Siang 3 Hari', 'Coffee Break', 'Akses Seluruh Sesi'],
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Peserta Online',
                'price' => 100000,
                'description' => 'Untuk peserta yang mengikuti secara daring (via Zoom)',
                'features' => ['E-Sertifikat Peserta', 'Akses Zoom Meeting', 'Rekaman Sesi Keynote', 'E-Prosiding (Akses Digital)'],
                'is_featured' => false,
                'is_active' => true,
            ],
        ];
        foreach ($packages as $i => $p) {
            RegistrationPackage::create(array_merge($p, ['conference_id' => $conference->id, 'sort_order' => $i]));
        }

        // -- Submission Guideline --
        SubmissionGuideline::create([
            'conference_id' => $conference->id,
            'content' => "## Panduan Penulisan Paper\n\nPaper yang dikirimkan harus merupakan karya asli dan belum pernah dipublikasikan sebelumnya. Semua paper akan melalui proses review oleh minimal 2 (dua) reviewer yang kompeten di bidangnya.\n\n### Format Penulisan\n- Paper ditulis dalam **Bahasa Indonesia** atau **Bahasa Inggris**\n- Menggunakan template yang telah disediakan\n- Font: Times New Roman, 12pt\n- Spasi: 1,15\n- Margin: 2,5 cm (semua sisi)\n- Ukuran kertas: A4\n\n### Struktur Paper\n1. **Judul** (maks. 15 kata)\n2. **Nama Penulis & Afiliasi**\n3. **Abstrak** (150-250 kata)\n4. **Kata Kunci** (3-5 kata)\n5. **Pendahuluan**\n6. **Metode**\n7. **Hasil dan Pembahasan**\n8. **Simpulan**\n9. **Daftar Pustaka**\n\n### Proses Submission\n1. Upload abstrak melalui sistem\n2. Tunggu notifikasi penerimaan abstrak\n3. Upload full paper setelah abstrak diterima\n4. Revisi paper sesuai masukan reviewer (jika diperlukan)\n5. Upload camera-ready version",
            'min_pages' => 6,
            'max_pages' => 12,
            'paper_format' => 'IEEE Conference Template',
            'citation_style' => 'APA 7th Edition',
            'additional_notes' => "Setiap paper akan di-review secara double-blind peer review. Paper yang diterima akan dipublikasikan dalam E-Prosiding ber-ISBN. Paper terbaik berkesempatan dipublikasikan di jurnal terakreditasi mitra LPKD-APJI.\n\nUntuk pertanyaan lebih lanjut, silakan hubungi panitia melalui email: lpkd.apji2026@uny.ac.id",
        ]);

        // ====================================================
        // SAMPLE PAPERS (OJS-style submissions)
        // ====================================================
        $author1 = User::where('email', 'author@prosiding.test')->first();
        $author2 = User::where('email', 'author2@prosiding.test')->first();
        $reviewer1 = User::where('email', 'reviewer@prosiding.test')->first();
        $reviewer2 = User::where('email', 'reviewer2@prosiding.test')->first();
        $reviewer3 = User::where('email', 'reviewer3@prosiding.test')->first();

        // Paper 1: Submitted (baru masuk)
        $paper1 = Paper::create([
            'user_id' => $author1->id,
            'title' => 'Implementasi Teaching Factory dalam Meningkatkan Kompetensi Siswa SMK Bidang Teknik Mesin',
            'abstract' => 'Penelitian ini bertujuan untuk menganalisis implementasi model Teaching Factory di SMK bidang Teknik Mesin dan pengaruhnya terhadap peningkatan kompetensi siswa. Metode penelitian menggunakan quasi-experiment dengan desain pretest-posttest control group design. Sampel penelitian melibatkan 120 siswa dari 3 SMK di Yogyakarta. Hasil penelitian menunjukkan bahwa implementasi Teaching Factory secara signifikan meningkatkan kompetensi teknis siswa sebesar 23,5% dibandingkan pembelajaran konvensional.',
            'keywords' => 'teaching factory, kompetensi, SMK, teknik mesin, pendidikan kejuruan',
            'topic' => 'Kurikulum & Pembelajaran Kejuruan',
            'authors_meta' => [
                ['name' => 'Penulis Satu', 'email' => 'author@prosiding.test', 'institution' => 'Universitas Gadjah Mada'],
                ['name' => 'Dr. Budi Prasetyo', 'email' => 'budi@ugm.ac.id', 'institution' => 'Universitas Gadjah Mada'],
            ],
            'status' => 'submitted',
            'submitted_at' => now()->subDays(3),
        ]);
        PaperFile::create(['paper_id' => $paper1->id, 'type' => 'abstract', 'file_path' => 'papers/dummy/abstrak-teaching-factory.pdf', 'original_name' => 'Abstrak_Teaching_Factory.pdf', 'mime_type' => 'application/pdf', 'file_size' => 245000]);
        PaperFile::create(['paper_id' => $paper1->id, 'type' => 'full_paper', 'file_path' => 'papers/dummy/fullpaper-teaching-factory.pdf', 'original_name' => 'FullPaper_Teaching_Factory_SMK.pdf', 'mime_type' => 'application/pdf', 'file_size' => 1520000]);

        // Paper 2: In Review (sedang direview)
        $paper2 = Paper::create([
            'user_id' => $author2->id,
            'title' => 'Pengembangan Media Pembelajaran Virtual Reality untuk Praktikum Kelistrikan Otomotif',
            'abstract' => 'Penelitian pengembangan ini menghasilkan media pembelajaran berbasis Virtual Reality (VR) untuk praktikum kelistrikan otomotif di SMK. Model pengembangan yang digunakan adalah ADDIE. Hasil validasi ahli media mendapat skor 4.5 dari 5.0, validasi ahli materi 4.3 dari 5.0, dan uji coba lapangan menunjukkan efektivitas tinggi dengan N-Gain sebesar 0.72.',
            'keywords' => 'virtual reality, media pembelajaran, kelistrikan otomotif, SMK',
            'topic' => 'Teknologi dan Media Pembelajaran',
            'authors_meta' => [
                ['name' => 'Penulis Dua', 'email' => 'author2@prosiding.test', 'institution' => 'Universitas Brawijaya'],
            ],
            'status' => 'in_review',
            'submitted_at' => now()->subDays(10),
        ]);
        PaperFile::create(['paper_id' => $paper2->id, 'type' => 'abstract', 'file_path' => 'papers/dummy/abstrak-vr-otomotif.pdf', 'original_name' => 'Abstrak_VR_Kelistrikan_Otomotif.pdf', 'mime_type' => 'application/pdf', 'file_size' => 198000]);
        PaperFile::create(['paper_id' => $paper2->id, 'type' => 'full_paper', 'file_path' => 'papers/dummy/fullpaper-vr-otomotif.pdf', 'original_name' => 'FullPaper_VR_Otomotif.pdf', 'mime_type' => 'application/pdf', 'file_size' => 2340000]);
        PaperFile::create(['paper_id' => $paper2->id, 'type' => 'turnitin', 'file_path' => 'papers/dummy/turnitin-vr-otomotif.pdf', 'original_name' => 'Turnitin_VR_Otomotif_12pct.pdf', 'mime_type' => 'application/pdf', 'file_size' => 890000]);

        // Assign reviewers to paper 2
        Review::create([
            'paper_id' => $paper2->id,
            'reviewer_id' => $reviewer1->id,
            'assigned_by' => $admin->id,
            'status' => 'completed',
            'comments' => 'Paper ini memiliki kontribusi yang baik dalam pengembangan media pembelajaran VR. Metodologi ADDIE diterapkan dengan cukup baik. Namun perlu ditambahkan pembahasan tentang keterbatasan penggunaan VR di SMK dengan fasilitas terbatas.',
            'comments_for_editor' => 'Paper layak diterima dengan minor revision. Penulis perlu menambahkan section diskusi tentang feasibility di sekolah dengan budget terbatas.',
            'recommendation' => 'minor_revision',
            'score' => 78,
            'reviewed_at' => now()->subDays(3),
        ]);
        Review::create([
            'paper_id' => $paper2->id,
            'reviewer_id' => $reviewer2->id,
            'assigned_by' => $admin->id,
            'status' => 'in_progress',
        ]);

        // Paper 3: Submitted (baru masuk, dari author1)
        $paper3 = Paper::create([
            'user_id' => $author1->id,
            'title' => 'Analisis Kesiapan Guru SMK dalam Mengimplementasikan Kurikulum Merdeka Belajar',
            'abstract' => 'Penelitian survei ini menganalisis tingkat kesiapan guru SMK di Provinsi DIY dalam mengimplementasikan Kurikulum Merdeka Belajar. Sampel penelitian terdiri dari 250 guru dari 15 SMK. Instrumen menggunakan angket dengan skala Likert. Hasil menunjukkan bahwa 65% guru merasa siap, 25% kurang siap, dan 10% belum siap.',
            'keywords' => 'kesiapan guru, kurikulum merdeka, SMK, pendidikan kejuruan',
            'topic' => 'Pengembangan SDM & Kompetensi Guru',
            'status' => 'submitted',
            'submitted_at' => now()->subDays(1),
        ]);
        PaperFile::create(['paper_id' => $paper3->id, 'type' => 'abstract', 'file_path' => 'papers/dummy/abstrak-kesiapan-guru.pdf', 'original_name' => 'Abstrak_Kesiapan_Guru_SMK.pdf', 'mime_type' => 'application/pdf', 'file_size' => 205000]);
        PaperFile::create(['paper_id' => $paper3->id, 'type' => 'full_paper', 'file_path' => 'papers/dummy/fullpaper-kesiapan-guru.pdf', 'original_name' => 'FullPaper_Kesiapan_Guru_Kurikulum_Merdeka.pdf', 'mime_type' => 'application/pdf', 'file_size' => 1870000]);

        // Paper 4: Revision Required 
        $paper4 = Paper::create([
            'user_id' => $author2->id,
            'title' => 'Efektivitas Program Magang Industri terhadap Kesiapan Kerja Lulusan Politeknik',
            'abstract' => 'Penelitian ini mengkaji efektivitas program magang industri dalam meningkatkan kesiapan kerja lulusan politeknik di Jawa Tengah. Pendekatan mixed methods digunakan dengan 180 responden alumni dan 25 perusahaan mitra. Hasilnya menunjukkan korelasi positif antara durasi magang dan kesiapan kerja lulusan (r=0.85, p<0.01).',
            'keywords' => 'magang industri, kesiapan kerja, politeknik, link and match',
            'topic' => 'Link and Match Industri',
            'status' => 'revision_required',
            'editor_notes' => 'Perlu revisi pada bagian metodologi dan tambahkan analisis perbandingan antar politeknik.',
            'submitted_at' => now()->subDays(14),
        ]);
        PaperFile::create(['paper_id' => $paper4->id, 'type' => 'abstract', 'file_path' => 'papers/dummy/abstrak-magang-industri.pdf', 'original_name' => 'Abstrak_Magang_Industri_Politeknik.pdf', 'mime_type' => 'application/pdf', 'file_size' => 215000]);
        PaperFile::create(['paper_id' => $paper4->id, 'type' => 'full_paper', 'file_path' => 'papers/dummy/fullpaper-magang-industri.pdf', 'original_name' => 'FullPaper_Efektivitas_Magang_Industri.pdf', 'mime_type' => 'application/pdf', 'file_size' => 2100000]);
        PaperFile::create(['paper_id' => $paper4->id, 'type' => 'turnitin', 'file_path' => 'papers/dummy/turnitin-magang.pdf', 'original_name' => 'Turnitin_Magang_Industri_15pct.pdf', 'mime_type' => 'application/pdf', 'file_size' => 780000]);

        Review::create([
            'paper_id' => $paper4->id,
            'reviewer_id' => $reviewer3->id,
            'assigned_by' => $admin->id,
            'status' => 'completed',
            'comments' => 'Topik menarik dan relevan. Namun bagian metodologi perlu diperkuat, terutama dalam justifikasi pemilihan sampel dan teknik analisis data.',
            'comments_for_editor' => 'Saya merekomendasikan major revision khususnya di bagian metodologi dan analisis data.',
            'recommendation' => 'major_revision',
            'score' => 62,
            'reviewed_at' => now()->subDays(5),
        ]);

        // Paper 5: Accepted
        $paper5 = Paper::create([
            'user_id' => $author1->id,
            'title' => 'Pengaruh Project-Based Learning terhadap Kreativitas dan Hasil Belajar Siswa SMK Bidang Seni Rupa',
            'abstract' => 'Studi eksperimental ini menguji pengaruh model Project-Based Learning (PjBL) terhadap kreativitas dan hasil belajar siswa SMK bidang Seni Rupa Digital. Desain penelitian menggunakan pretest-posttest control group. PjBL terbukti signifikan meningkatkan kreativitas (effect size d=0.95) dan hasil belajar (effect size d=0.82).',
            'keywords' => 'project-based learning, kreativitas, seni rupa digital, SMK',
            'topic' => 'Kurikulum & Pembelajaran Kejuruan',
            'status' => 'accepted',
            'submitted_at' => now()->subDays(20),
            'accepted_at' => now()->subDays(2),
        ]);
        PaperFile::create(['paper_id' => $paper5->id, 'type' => 'abstract', 'file_path' => 'papers/dummy/abstrak-pjbl-seni.pdf', 'original_name' => 'Abstrak_PjBL_Seni_Rupa.pdf', 'mime_type' => 'application/pdf', 'file_size' => 190000]);
        PaperFile::create(['paper_id' => $paper5->id, 'type' => 'full_paper', 'file_path' => 'papers/dummy/fullpaper-pjbl-seni.pdf', 'original_name' => 'FullPaper_PjBL_Kreativitas_SMK.pdf', 'mime_type' => 'application/pdf', 'file_size' => 1950000]);

        Review::create([
            'paper_id' => $paper5->id,
            'reviewer_id' => $reviewer1->id,
            'assigned_by' => $admin->id,
            'status' => 'completed',
            'comments' => 'Paper yang sangat baik dengan metodologi yang kuat. Analisis data komprehensif dan temuan yang signifikan. Layak diterima.',
            'recommendation' => 'accept',
            'score' => 90,
            'reviewed_at' => now()->subDays(7),
        ]);
        Review::create([
            'paper_id' => $paper5->id,
            'reviewer_id' => $reviewer2->id,
            'assigned_by' => $admin->id,
            'status' => 'completed',
            'comments' => 'Desain eksperimental solid, effect size menunjukkan dampak yang besar. Kontribusi penting untuk literatur PjBL di SMK.',
            'recommendation' => 'accept',
            'score' => 88,
            'reviewed_at' => now()->subDays(6),
        ]);

        // Paper 6: Rejected (archived)
        $paper6 = Paper::create([
            'user_id' => $author2->id,
            'title' => 'Studi Komparatif Sistem Pendidikan Vokasi Indonesia dan Jerman',
            'abstract' => 'Penelitian ini membandingkan sistem pendidikan vokasi di Indonesia dan Jerman dari aspek kebijakan, kurikulum, dan employability lulusan.',
            'keywords' => 'pendidikan vokasi, komparatif, Indonesia, Jerman',
            'topic' => 'Manajemen Pendidikan Kejuruan',
            'status' => 'rejected',
            'editor_notes' => 'Paper kurang orisinal dan analisis perbandingan terlalu dangkal.',
            'submitted_at' => now()->subDays(30),
        ]);
        PaperFile::create(['paper_id' => $paper6->id, 'type' => 'abstract', 'file_path' => 'papers/dummy/abstrak-komparatif-vokasi.pdf', 'original_name' => 'Abstrak_Komparatif_Vokasi_ID_DE.pdf', 'mime_type' => 'application/pdf', 'file_size' => 175000]);

        // Paper 7: Completed (archived)
        $paper7 = Paper::create([
            'user_id' => $author1->id,
            'title' => 'Pengembangan E-Module Interaktif Berbasis Gamifikasi untuk Mata Pelajaran Pemrograman Web di SMK',
            'abstract' => 'Penelitian R&D ini mengembangkan e-module interaktif berbasis gamifikasi untuk meningkatkan motivasi dan pemahaman siswa SMK dalam mata pelajaran Pemrograman Web. Model pengembangan 4D (Define, Design, Develop, Disseminate) digunakan.',
            'keywords' => 'e-module, gamifikasi, pemrograman web, SMK, R&D',
            'topic' => 'Teknologi dan Media Pembelajaran',
            'status' => 'completed',
            'submitted_at' => now()->subDays(45),
            'accepted_at' => now()->subDays(15),
        ]);
        PaperFile::create(['paper_id' => $paper7->id, 'type' => 'abstract', 'file_path' => 'papers/dummy/abstrak-emodule-gamifikasi.pdf', 'original_name' => 'Abstrak_EModule_Gamifikasi.pdf', 'mime_type' => 'application/pdf', 'file_size' => 210000]);
        PaperFile::create(['paper_id' => $paper7->id, 'type' => 'full_paper', 'file_path' => 'papers/dummy/fullpaper-emodule-gamifikasi.pdf', 'original_name' => 'FullPaper_EModule_Gamifikasi_Web.pdf', 'mime_type' => 'application/pdf', 'file_size' => 2450000]);
        PaperFile::create(['paper_id' => $paper7->id, 'type' => 'turnitin', 'file_path' => 'papers/dummy/turnitin-emodule.pdf', 'original_name' => 'Turnitin_EModule_9pct.pdf', 'mime_type' => 'application/pdf', 'file_size' => 650000]);

        // Paper 8: Submitted  
        $paper8 = Paper::create([
            'user_id' => $author2->id,
            'title' => 'Peran Sertifikasi Kompetensi dalam Meningkatkan Daya Saing Lulusan SMK di Era Industri 4.0',
            'abstract' => 'Penelitian ini mengeksplorasi peran sertifikasi kompetensi profesional terhadap daya saing lulusan SMK di pasar kerja era Industri 4.0.',
            'keywords' => 'sertifikasi kompetensi, industri 4.0, SMK, daya saing',
            'topic' => 'Link and Match Industri',
            'status' => 'submitted',
            'submitted_at' => now()->subDays(2),
        ]);
        PaperFile::create(['paper_id' => $paper8->id, 'type' => 'abstract', 'file_path' => 'papers/dummy/abstrak-sertifikasi-kompetensi.pdf', 'original_name' => 'Abstrak_Sertifikasi_Kompetensi_I40.pdf', 'mime_type' => 'application/pdf', 'file_size' => 195000]);
    }

    /**
     * Create dummy PDF files so download links work during development.
     */
    private function createDummyPdfFiles(): void
    {
        $dummyFiles = [
            'abstrak-teaching-factory.pdf',
            'fullpaper-teaching-factory.pdf',
            'abstrak-vr-otomotif.pdf',
            'fullpaper-vr-otomotif.pdf',
            'turnitin-vr-otomotif.pdf',
            'abstrak-kesiapan-guru.pdf',
            'fullpaper-kesiapan-guru.pdf',
            'abstrak-magang-industri.pdf',
            'fullpaper-magang-industri.pdf',
            'turnitin-magang.pdf',
            'abstrak-pjbl-seni.pdf',
            'fullpaper-pjbl-seni.pdf',
            'abstrak-komparatif-vokasi.pdf',
            'abstrak-emodule-gamifikasi.pdf',
            'fullpaper-emodule-gamifikasi.pdf',
            'turnitin-emodule.pdf',
            'abstrak-sertifikasi-kompetensi.pdf',
        ];

        $dir = storage_path('app/public/papers/dummy');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        foreach ($dummyFiles as $f) {
            $path = $dir . '/' . $f;
            if (!file_exists($path)) {
                $title = ucwords(str_replace(['-', '.pdf'], [' ', ''], $f));
                $stream = "BT /F1 24 Tf 72 700 Td ({$title}) Tj ET";
                $pdf = "%PDF-1.4\n";
                $pdf .= "1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n";
                $pdf .= "2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n";
                $pdf .= "3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 612 792]/Contents 4 0 R/Resources<</Font<</F1 5 0 R>>>>>>endobj\n";
                $pdf .= "4 0 obj<</Length " . strlen($stream) . ">>stream\n{$stream}\nendstream endobj\n";
                $pdf .= "5 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj\n";
                $xref = strlen($pdf);
                $pdf .= "xref\n0 6\n0000000000 65535 f \ntrailer<</Size 6/Root 1 0 R>>\nstartxref\n{$xref}\n%%EOF";
                file_put_contents($path, $pdf);
            }
        }
    }
}
