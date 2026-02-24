<?php

namespace App\Console\Commands;

use App\Models\Conference;
use App\Models\ImportantDate;
use App\Models\Paper;
use App\Models\Review;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDeadlineReminders extends Command
{
    protected $signature   = 'reminders:send-deadlines {--dry-run : Preview tanpa mengirim}';
    protected $description = 'Kirim pengingat deadline H-3 dan H-1 ke author dan reviewer';

    public function handle(): int
    {
        $dryRun    = $this->option('dry-run');
        $today     = now()->toDateString();
        $in1Day    = now()->addDay()->toDateString();
        $in3Days   = now()->addDays(3)->toDateString();

        $this->info("Pengecekan deadline pada: $today");
        $this->info("Mencari deadline pada: $in1Day (H-1) dan $in3Days (H-3)");

        $targetDates = [$in1Day, $in3Days];
        $sent = 0;

        // ─── Important Dates ───
        $deadlines = ImportantDate::whereIn(\DB::raw('DATE(date)'), $targetDates)->with('conference')->get();

        foreach ($deadlines as $deadline) {
            $conf    = $deadline->conference;
            $daysLeft= now()->diffInDays($deadline->date);
            $label   = "H-{$daysLeft}: {$deadline->title}";

            // Notify all authors of this conference
            $authors = Paper::where('conference_id', $conf->id)
                ->whereNotIn('status', ['rejected','completed'])
                ->with('user:id,name,email')
                ->get()
                ->pluck('user')
                ->unique('id');

            foreach ($authors as $author) {
                $message = "Pengingat: *{$deadline->title}* untuk {$conf->name} tinggal **{$daysLeft} hari** lagi (tanggal {$deadline->date}).";

                if (!$dryRun) {
                    Notification::firstOrCreate(
                        [
                            'user_id' => $author->id,
                            'type'    => 'deadline_reminder',
                            'title'   => $label,
                        ],
                        [
                            'message' => $message,
                            'data'    => ['conference_id' => $conf->id, 'deadline_date' => $deadline->date],
                        ]
                    );
                }

                $this->line("  ✓ Notif → {$author->name} ({$label})");
                $sent++;
            }
        }

        // ─── Revision Deadlines ───
        $revisions = \App\Models\RevisionRequest::whereNull('resolved_at')
            ->whereNotNull('deadline')
            ->whereIn(\DB::raw('DATE(deadline)'), $targetDates)
            ->with(['paper.user:id,name,email', 'paper:id,title,user_id'])
            ->get();

        foreach ($revisions as $rev) {
            $daysLeft = now()->diffInDays($rev->deadline);
            $message  = "Pengingat: Deadline revisi paper \"" . \Illuminate\Support\Str::limit($rev->paper->title, 60) . "\" tinggal **{$daysLeft} hari**.";

            if (!$dryRun) {
                Notification::firstOrCreate(
                    [
                        'user_id' => $rev->paper->user_id,
                        'type'    => 'revision_deadline_reminder',
                        'title'   => "H-{$daysLeft}: Deadline Revisi",
                    ],
                    [
                        'message' => $message,
                        'data'    => ['paper_id' => $rev->paper_id],
                    ]
                );
            }

            $this->line("  ✓ Revision reminder → {$rev->paper->user->name}");
            $sent++;
        }

        $this->newLine();
        if ($dryRun) {
            $this->warn("[DRY RUN] {$sent} notifikasi akan dikirim (tidak benar-benar dikirim).");
        } else {
            $this->info("✅ {$sent} notifikasi deadline berhasil dikirim.");
        }

        return Command::SUCCESS;
    }
}
