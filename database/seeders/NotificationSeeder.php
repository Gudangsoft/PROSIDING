<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // Create sample notifications for each user
            Notification::create([
                'user_id' => $user->id,
                'type' => 'success',
                'title' => 'Selamat Datang!',
                'message' => 'Terima kasih telah bergabung dengan sistem prosiding LPKD-APJI.',
                'action_url' => route('dashboard'),
                'action_text' => 'Lihat Dashboard',
            ]);

            if ($user->isAuthor()) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'info',
                    'title' => 'Submit Paper Anda',
                    'message' => 'Jangan lupa untuk submit paper Anda sebelum deadline.',
                    'action_url' => route('author.submit'),
                    'action_text' => 'Submit Sekarang',
                ]);
            }

            if ($user->isReviewer()) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'warning',
                    'title' => 'Review Menunggu',
                    'message' => 'Anda memiliki paper yang perlu direview.',
                    'action_url' => route('reviewer.reviews'),
                    'action_text' => 'Lihat Review',
                ]);
            }

            if ($user->isAdminOrEditor()) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'info',
                    'title' => 'Paper Baru Masuk',
                    'message' => 'Ada paper baru yang perlu diverifikasi.',
                    'action_url' => route('admin.papers'),
                    'action_text' => 'Lihat Paper',
                ]);
            }
        }

        $this->command->info('Sample notifications created successfully!');
    }
}
