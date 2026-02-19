<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Paper;
use App\Models\PaperFile;

class PaperTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create author
        $author = User::firstOrCreate(
            ['email' => 'author@prosiding.test'],
            [
                'name' => 'Test Author',
                'password' => bcrypt('password'),
                'role' => 'author',
                'institution' => 'Universitas Testing',
                'phone' => '081234567890',
            ]
        );

        // Create papers with different statuses
        $papers = [
            [
                'title' => 'Implementasi Machine Learning dalam Pendidikan',
                'status' => 'submitted',
                'abstract' => 'Penelitian ini membahas implementasi machine learning dalam sistem pendidikan modern untuk meningkatkan efektivitas pembelajaran.',
            ],
            [
                'title' => 'Analisis Big Data untuk Optimasi Sistem Informasi',
                'status' => 'in_review',
                'abstract' => 'Paper ini menganalisis penggunaan big data dalam optimasi sistem informasi perusahaan.',
            ],
            [
                'title' => 'Pengembangan Aplikasi Mobile untuk E-Commerce',
                'status' => 'revision_required',
                'abstract' => 'Studi kasus pengembangan aplikasi mobile e-commerce dengan teknologi React Native dan Node.js.',
                'editor_notes' => 'Mohon perbaiki bagian metodologi dan tambahkan referensi terbaru. Hasil penelitian perlu dijelaskan lebih detail.',
            ],
            [
                'title' => 'Keamanan Siber pada Infrastruktur Cloud Computing',
                'status' => 'accepted',
                'abstract' => 'Penelitian tentang strategi keamanan siber pada infrastruktur cloud computing di era digital.',
                'accepted_at' => now()->subDays(2),
            ],
            [
                'title' => 'Internet of Things untuk Smart City',
                'status' => 'payment_pending',
                'abstract' => 'Implementasi Internet of Things dalam pengembangan konsep smart city di Indonesia.',
                'accepted_at' => now()->subDays(5),
            ],
        ];

        foreach ($papers as $index => $paperData) {
            $paper = Paper::create([
                'user_id' => $author->id,
                'title' => $paperData['title'],
                'abstract' => $paperData['abstract'],
                'keywords' => 'teknologi, sistem informasi, penelitian',
                'topic' => 'Teknologi Informasi',
                'status' => $paperData['status'],
                'submitted_at' => now()->subDays(10 - $index),
                'editor_notes' => $paperData['editor_notes'] ?? null,
                'accepted_at' => $paperData['accepted_at'] ?? null,
            ]);

            // Create initial paper file
            PaperFile::create([
                'paper_id' => $paper->id,
                'type' => 'full_paper',
                'file_path' => 'papers/' . $paper->id . '/full_paper.pdf',
                'original_name' => 'full_paper_' . $paper->id . '.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => rand(500000, 2000000),
            ]);

            $this->command->info("Created paper: {$paperData['title']} (ID: {$paper->id}, Status: {$paperData['status']})");
        }

        $this->command->info("\n✅ Paper testing data created successfully!");
        $this->command->info("📧 Login as: author@prosiding.test");
        $this->command->info("🔑 Password: password");
        $this->command->info("\n📝 Papers created:");
        
        $authorPapers = Paper::where('user_id', $author->id)->get();
        foreach ($authorPapers as $paper) {
            $this->command->info("   - ID {$paper->id}: {$paper->title} ({$paper->status})");
        }
    }
}
