<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\ImportantDate;
use App\Models\Committee;
use App\Models\Topic;
use App\Models\KeynoteSpeaker;
use App\Models\RegistrationPackage;
use App\Models\SubmissionGuideline;
use App\Models\DeliverableTemplate;
use App\Models\JournalPublication;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ConferenceForm extends Component
{
    use WithFileUploads;

    public ?Conference $conference = null;
    public bool $isEdit = false;

    // Conference fields
    public string $name = '';
    public string $acronym = '';
    public string $theme = '';
    public string $description = '';
    public string $start_date = '';
    public string $start_time = '';
    public string $end_date = '';
    public string $end_time = '';
    public string $venue = '';
    public string $venue_type = 'offline';
    public string $online_url = '';
    public string $city = '';
    public string $organizer = '';
    public string $status = 'draft';
    public string $loaGenerationMode = 'manual';
    public string $certificateGenerationMode = 'manual';
    public $cover_image;
    public $logo;
    public $brochure;

    // Payment info
    public string $payment_bank_name = '';
    public string $payment_bank_account = '';
    public string $payment_account_holder = '';
    public string $payment_contact_phone = '';
    public string $payment_instructions = '';
    public array $paymentMethods = []; // Multiple payment methods with details

    // Existing image paths (for preview)
    public ?string $existing_cover_image = null;
    public ?string $existing_logo = null;
    public ?string $existing_brochure = null;
    public ?string $existing_template_file = null;

    // Sub-data arrays
    public array $importantDates = [];
    public array $committees = [];
    public array $topics = [];
    public array $speakers = [];
    public array $speakerPhotos = []; // temp file uploads keyed by speaker index
    public array $packages = [];      // registration packages
    public array $reviewers = [];     // reviewer users

    // Guideline
    public string $guideline_content = '';
    public ?int $max_pages = null;
    public ?int $min_pages = null;
    public string $paper_format = '';
    public string $citation_style = '';
    public string $additional_notes = '';
    public $template_file;

    // Deliverable templates
    public array $deliverableTemplates = [];
    public array $templateFiles = []; // temp file uploads keyed by template index

    // Journal publications
    public array $journals = [];
    public array $journalLogos = []; // temp file uploads keyed by journal index

    // Active tab
    public string $activeTab = 'general';

    public function mount(?Conference $conference = null)
    {
        if ($conference && $conference->exists) {
            $this->isEdit = true;
            $this->conference = $conference;
            $this->fill([
                'name' => $conference->name,
                'acronym' => $conference->acronym ?? '',
                'theme' => $conference->theme ?? '',
                'description' => $conference->description ?? '',
                'start_date' => $conference->start_date?->format('Y-m-d') ?? '',
                'start_time' => $conference->start_time ?? '',
                'end_date' => $conference->end_date?->format('Y-m-d') ?? '',
                'end_time' => $conference->end_time ?? '',
                'venue' => $conference->venue ?? '',
                'venue_type' => $conference->venue_type ?? 'offline',
                'online_url' => $conference->online_url ?? '',
                'city' => $conference->city ?? '',
                'organizer' => $conference->organizer ?? '',
                'status' => $conference->status,
                'loaGenerationMode' => $conference->loa_generation_mode ?? 'manual',
                'certificateGenerationMode' => $conference->certificate_generation_mode ?? 'manual',
                'existing_cover_image' => $conference->cover_image,
                'existing_logo' => $conference->logo,
                'existing_brochure' => $conference->brochure,
            ]);

            // Load important dates
            $this->importantDates = $conference->importantDates->map(fn($d) => [
                'id' => $d->id, 'title' => $d->title, 'date' => $d->date->format('Y-m-d'),
                'type' => $d->type, 'description' => $d->description ?? '',
            ])->toArray();

            // Load committees
            $this->committees = $conference->committees->map(fn($c) => [
                'id' => $c->id, 'name' => $c->name, 'title' => $c->title ?? '',
                'institution' => $c->institution ?? '', 'type' => $c->type, 'role' => $c->role ?? '',
            ])->toArray();

            // Load topics
            $this->topics = $conference->topics->map(fn($t) => [
                'id' => $t->id, 'name' => $t->name, 'description' => $t->description ?? '', 'code' => $t->code ?? '',
            ])->toArray();

            // Load speakers
            $this->speakers = $conference->keynoteSpeakers->map(fn($s) => [
                'id' => $s->id, 'type' => $s->type ?? 'keynote_speaker', 'name' => $s->name, 'title' => $s->title ?? '',
                'institution' => $s->institution ?? '', 'topic' => $s->topic ?? '', 'bio' => $s->bio ?? '',
                'existing_photo' => $s->photo ?? null,
            ])->toArray();

            // Load guideline
            if ($guideline = $conference->guideline) {
                $this->guideline_content = $guideline->content ?? '';
                $this->max_pages = $guideline->max_pages;
                $this->min_pages = $guideline->min_pages;
                $this->paper_format = $guideline->paper_format ?? '';
                $this->citation_style = $guideline->citation_style ?? '';
                $this->additional_notes = $guideline->additional_notes ?? '';
                $this->existing_template_file = $guideline->template_file;
            }

            // Load deliverable templates
            $this->deliverableTemplates = $conference->deliverableTemplates->map(fn($t) => [
                'id' => $t->id,
                'type' => $t->type,
                'label' => $t->label,
                'description' => $t->description ?? '',
                'existing_file' => $t->file_path,
                'original_name' => $t->original_name,
            ])->toArray();

            // Load journal publications
            $this->journals = $conference->journalPublications->map(fn($j) => [
                'id' => $j->id,
                'name' => $j->name,
                'sinta_rank' => $j->sinta_rank ?? '',
                'url' => $j->url ?? '',
                'description' => $j->description ?? '',
                'existing_logo' => $j->logo,
                'is_active' => $j->is_active,
            ])->toArray();

            // Load payment info
            $this->payment_bank_name = $conference->payment_bank_name ?? '';
            $this->payment_bank_account = $conference->payment_bank_account ?? '';
            $this->payment_account_holder = $conference->payment_account_holder ?? '';
            $this->payment_contact_phone = $conference->payment_contact_phone ?? '';
            $this->payment_instructions = $conference->payment_instructions ?? '';
            
            // Load payment methods
            $this->paymentMethods = $conference->payment_methods ?? [];

            // Load registration packages
            $this->packages = $conference->registrationPackages->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'description' => $p->description ?? '',
                'features' => implode("\n", $p->features ?? []),
                'is_featured' => $p->is_featured,
                'is_active' => $p->is_active,
            ])->toArray();
        }

        // Load all reviewers (global, not conference-specific)
        $this->reviewers = User::where('role', 'reviewer')->get()->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'institution' => $u->institution ?? '',
            'phone' => $u->phone ?? '',
        ])->toArray();
    }

    // -- Image removal --
    public function removeCoverImage()
    {
        if ($this->existing_cover_image && $this->isEdit) {
            Storage::disk('public')->delete($this->existing_cover_image);
            $this->conference->update(['cover_image' => null]);
        }
        $this->existing_cover_image = null;
        $this->cover_image = null;
    }

    public function removeLogo()
    {
        if ($this->existing_logo && $this->isEdit) {
            Storage::disk('public')->delete($this->existing_logo);
            $this->conference->update(['logo' => null]);
        }
        $this->existing_logo = null;
        $this->logo = null;
    }

    public function removeBrochure()
    {
        if ($this->existing_brochure && $this->isEdit) {
            Storage::disk('public')->delete($this->existing_brochure);
            $this->conference->update(['brochure' => null]);
        }
        $this->existing_brochure = null;
        $this->brochure = null;
    }

    public function removeTemplateFile()
    {
        if ($this->existing_template_file && $this->isEdit) {
            Storage::disk('public')->delete($this->existing_template_file);
            $this->conference->guideline?->update(['template_file' => null]);
        }
        $this->existing_template_file = null;
        $this->template_file = null;
    }

    // -- Important Dates --
    public function addDate()
    {
        $this->importantDates[] = ['id' => null, 'title' => '', 'date' => '', 'type' => 'other', 'description' => ''];
    }

    public function removeDate($index)
    {
        if (isset($this->importantDates[$index]['id']) && $this->importantDates[$index]['id']) {
            ImportantDate::find($this->importantDates[$index]['id'])?->delete();
        }
        unset($this->importantDates[$index]);
        $this->importantDates = array_values($this->importantDates);
    }

    // -- Committees --
    public function addCommittee()
    {
        $this->committees[] = ['id' => null, 'name' => '', 'title' => '', 'institution' => '', 'type' => 'organizing', 'role' => ''];
    }

    public function removeCommittee($index)
    {
        if (isset($this->committees[$index]['id']) && $this->committees[$index]['id']) {
            Committee::find($this->committees[$index]['id'])?->delete();
        }
        unset($this->committees[$index]);
        $this->committees = array_values($this->committees);
    }

    // -- Topics --
    public function addTopic()
    {
        $this->topics[] = ['id' => null, 'name' => '', 'description' => '', 'code' => ''];
    }

    public function removeTopic($index)
    {
        if (isset($this->topics[$index]['id']) && $this->topics[$index]['id']) {
            Topic::find($this->topics[$index]['id'])?->delete();
        }
        unset($this->topics[$index]);
        $this->topics = array_values($this->topics);
    }

    // -- Speakers --
    public function addSpeaker($type = 'keynote_speaker')
    {
        $this->speakers[] = ['id' => null, 'type' => $type, 'name' => '', 'title' => '', 'institution' => '', 'topic' => '', 'bio' => '', 'existing_photo' => null];
    }

    public function removeSpeaker($index)
    {
        if (isset($this->speakers[$index]['id']) && $this->speakers[$index]['id']) {
            $speaker = KeynoteSpeaker::find($this->speakers[$index]['id']);
            if ($speaker && $speaker->photo) {
                Storage::disk('public')->delete($speaker->photo);
            }
            $speaker?->delete();
        }
        unset($this->speakers[$index]);
        $this->speakers = array_values($this->speakers);
        unset($this->speakerPhotos[$index]);
        // Reindex speakerPhotos
        $reindexed = [];
        $idx = 0;
        foreach (array_keys($this->speakers) as $key) {
            if (isset($this->speakerPhotos[$key])) {
                $reindexed[$idx] = $this->speakerPhotos[$key];
            }
            $idx++;
        }
        $this->speakerPhotos = $reindexed;
    }

    public function removeSpeakerPhoto($index)
    {
        // Remove existing photo from storage
        if (!empty($this->speakers[$index]['existing_photo'])) {
            Storage::disk('public')->delete($this->speakers[$index]['existing_photo']);
            if (!empty($this->speakers[$index]['id'])) {
                KeynoteSpeaker::find($this->speakers[$index]['id'])?->update(['photo' => null]);
            }
            $this->speakers[$index]['existing_photo'] = null;
        }
        // Clear any new upload
        unset($this->speakerPhotos[$index]);
    }

    // -- Registration Packages --
    public function addPackage()
    {
        $this->packages[] = ['id' => null, 'name' => '', 'price' => 0, 'description' => '', 'features' => '', 'is_featured' => false, 'is_active' => true];
    }

    // -- Deliverable templates --
    public function addTemplate()
    {
        $this->deliverableTemplates[] = [
            'id' => null, 'type' => 'poster', 'label' => '', 'description' => '',
            'existing_file' => null, 'original_name' => '',
        ];
    }

    public function removeTemplate($index)
    {
        if (isset($this->deliverableTemplates[$index])) {
            if (!empty($this->deliverableTemplates[$index]['id'])) {
                $template = DeliverableTemplate::find($this->deliverableTemplates[$index]['id']);
                if ($template) {
                    Storage::disk('public')->delete($template->file_path);
                    $template->delete();
                }
            }
            unset($this->deliverableTemplates[$index]);
            $this->deliverableTemplates = array_values($this->deliverableTemplates);
            unset($this->templateFiles[$index]);
            $this->templateFiles = array_values($this->templateFiles);
        }
    }

    public function removePackage($index)
    {
        if (isset($this->packages[$index]['id']) && $this->packages[$index]['id']) {
            RegistrationPackage::find($this->packages[$index]['id'])?->delete();
        }
        unset($this->packages[$index]);
        $this->packages = array_values($this->packages);
    }

    // -- Payment Methods --
    public function addPaymentMethod()
    {
        $this->paymentMethods[] = [
            'type' => 'Bank Transfer',
            'name' => '',
            'account_number' => '',
            'account_holder' => '',
            'amount' => '',
            'instructions' => '',
            'is_active' => true,
        ];
    }

    public function removePaymentMethod($index)
    {
        unset($this->paymentMethods[$index]);
        $this->paymentMethods = array_values($this->paymentMethods);
    }

    // -- Journal Publications --
    public function addJournal()
    {
        $this->journals[] = ['id' => null, 'name' => '', 'sinta_rank' => '', 'url' => '', 'description' => '', 'existing_logo' => null, 'is_active' => true];
    }

    public function removeJournal($index)
    {
        if (isset($this->journals[$index]['id']) && $this->journals[$index]['id']) {
            $journal = JournalPublication::find($this->journals[$index]['id']);
            if ($journal && $journal->logo) {
                Storage::disk('public')->delete($journal->logo);
            }
            $journal?->delete();
        }
        unset($this->journals[$index]);
        $this->journals = array_values($this->journals);
        unset($this->journalLogos[$index]);
        $this->journalLogos = array_values($this->journalLogos);
    }

    public function removeJournalLogo($index)
    {
        if (isset($this->journals[$index]['existing_logo']) && $this->journals[$index]['existing_logo']) {
            if ($this->isEdit && !empty($this->journals[$index]['id'])) {
                Storage::disk('public')->delete($this->journals[$index]['existing_logo']);
                JournalPublication::find($this->journals[$index]['id'])?->update(['logo' => null]);
            }
            $this->journals[$index]['existing_logo'] = null;
        }
        unset($this->journalLogos[$index]);
    }

    // -- Reviewers --
    public function addReviewer()
    {
        $this->reviewers[] = ['id' => null, 'name' => '', 'email' => '', 'institution' => '', 'phone' => ''];
    }

    public function removeReviewer($index)
    {
        if (isset($this->reviewers[$index]['id']) && $this->reviewers[$index]['id']) {
            User::find($this->reviewers[$index]['id'])?->delete();
        }
        unset($this->reviewers[$index]);
        $this->reviewers = array_values($this->reviewers);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'acronym' => 'nullable|string|max:50',
            'theme' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'end_time' => 'nullable|date_format:H:i',
            'venue' => 'nullable|string|max:255',
            'venue_type' => 'required|in:offline,online,hybrid',
            'online_url' => 'nullable|url|max:500',
            'city' => 'nullable|string|max:100',
            'organizer' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|max:2048',
            'logo' => 'nullable|image|max:1024',
            'brochure' => 'nullable|image|max:5120',
            'template_file' => 'nullable|file|max:5120',
            'speakerPhotos.*' => 'nullable|image|max:1024',
            'templateFiles.*' => 'nullable|file|max:10240',
        ], [
            'name.required' => 'Nama kegiatan wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai.',
            'cover_image.max' => 'Cover image maksimal 2MB.',
            'logo.max' => 'Logo maksimal 1MB.',
            'brochure.max' => 'Brosur/pamflet maksimal 5MB.',
            'brochure.image' => 'Brosur/pamflet harus berupa gambar.',
            'template_file.max' => 'Template file maksimal 5MB.',
            'speakerPhotos.*.max' => 'Foto speaker maksimal 1MB.',
            'speakerPhotos.*.image' => 'Foto speaker harus berupa gambar.',
            'templateFiles.*.max' => 'File template maksimal 10MB.',
        ]);

        $data = [
            'name' => $this->name,
            'acronym' => $this->acronym ?: null,
            'theme' => $this->theme ?: null,
            'description' => $this->description ?: null,
            'start_date' => $this->start_date ?: null,
            'start_time' => $this->start_time ?: null,
            'end_date' => $this->end_date ?: null,
            'end_time' => $this->end_time ?: null,
            'venue' => $this->venue ?: null,
            'venue_type' => $this->venue_type,
            'online_url' => $this->online_url ?: null,
            'city' => $this->city ?: null,
            'organizer' => $this->organizer ?: null,
            'payment_bank_name' => $this->payment_bank_name ?: null,
            'payment_bank_account' => $this->payment_bank_account ?: null,
            'payment_account_holder' => $this->payment_account_holder ?: null,
            'payment_contact_phone' => $this->payment_contact_phone ?: null,
            'payment_instructions' => $this->payment_instructions ?: null,
            'payment_methods' => !empty($this->paymentMethods) ? $this->paymentMethods : null,
            'status' => $this->status,
            'loa_generation_mode' => $this->loaGenerationMode,
            'certificate_generation_mode' => $this->certificateGenerationMode,
        ];

        if ($this->cover_image) {
            // Delete old file
            if ($this->isEdit && $this->conference->cover_image) {
                Storage::disk('public')->delete($this->conference->cover_image);
            }
            $data['cover_image'] = $this->cover_image->store('conferences', 'public');
        }
        if ($this->logo) {
            if ($this->isEdit && $this->conference->logo) {
                Storage::disk('public')->delete($this->conference->logo);
            }
            $data['logo'] = $this->logo->store('conferences', 'public');
        }
        if ($this->brochure) {
            if ($this->isEdit && $this->conference->brochure) {
                Storage::disk('public')->delete($this->conference->brochure);
            }
            $data['brochure'] = $this->brochure->store('conferences', 'public');
        }

        if ($this->isEdit) {
            $oldStatus = $this->conference->status;
            $this->conference->update($data);
            $conference = $this->conference;
            
            // Send notification if conference status changed to published
            if ($oldStatus !== 'published' && $conference->status === 'published') {
                $allUsers = User::all();
                $allUserIds = $allUsers->pluck('id')->toArray();
                
                \App\Models\Notification::createForUsers(
                    userIds: $allUserIds,
                    type: 'info',
                    title: 'Kegiatan Dipublikasikan! 📢',
                    message: 'Kegiatan "' . $conference->name . '" telah dipublikasikan. ' . ($conference->start_date ? 'Dimulai pada ' . \Carbon\Carbon::parse($conference->start_date)->format('d M Y') . '.' : 'Daftarkan diri Anda sekarang!'),
                    actionUrl: url('/'),
                    actionText: 'Lihat Detail'
                );

                // Send email to all users
                try {
                    foreach ($allUsers as $user) {
                        \Illuminate\Support\Facades\Mail::to($user->email)->send(
                            new \App\Mail\NewEventMail(
                                $user->name,
                                $conference->name,
                                $conference->start_date ? \Carbon\Carbon::parse($conference->start_date)->format('d M Y') : null,
                                url('/')
                            )
                        );
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send event published emails: ' . $e->getMessage());
                }
            }
        } else {
            $data['created_by'] = Auth::id();
            $conference = Conference::create($data);
            
            // Send notification to all users about new conference/event
            if ($conference->status === 'published') {
                $allUsers = User::all();
                $allUserIds = $allUsers->pluck('id')->toArray();
                
                \App\Models\Notification::createForUsers(
                    userIds: $allUserIds,
                    type: 'info',
                    title: 'Kegiatan Baru Tersedia! 📢',
                    message: 'Kegiatan baru "' . $conference->name . '" telah dipublikasikan. ' . ($conference->start_date ? 'Dimulai pada ' . \Carbon\Carbon::parse($conference->start_date)->format('d M Y') . '.' : 'Daftarkan diri Anda sekarang!'),
                    actionUrl: url('/'),
                    actionText: 'Lihat Detail'
                );

                // Send email to all users
                try {
                    foreach ($allUsers as $user) {
                        \Illuminate\Support\Facades\Mail::to($user->email)->send(
                            new \App\Mail\NewEventMail(
                                $user->name,
                                $conference->name,
                                $conference->start_date ? \Carbon\Carbon::parse($conference->start_date)->format('d M Y') : null,
                                url('/')
                            )
                        );
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send new event emails: ' . $e->getMessage());
                }
            }
        }

        // Save important dates
        $existingDateIds = collect($this->importantDates)->pluck('id')->filter()->toArray();
        if ($this->isEdit) {
            $conference->importantDates()->whereNotIn('id', $existingDateIds)->delete();
        }
        foreach ($this->importantDates as $i => $date) {
            if (empty($date['title']) || empty($date['date'])) continue;
            $dateData = [
                'conference_id' => $conference->id,
                'title' => $date['title'],
                'date' => $date['date'],
                'type' => $date['type'],
                'description' => $date['description'] ?? null,
                'sort_order' => $i,
            ];
            if (!empty($date['id'])) {
                ImportantDate::find($date['id'])?->update($dateData);
            } else {
                ImportantDate::create($dateData);
            }
        }

        // Save committees
        $existingCommIds = collect($this->committees)->pluck('id')->filter()->toArray();
        if ($this->isEdit) {
            $conference->committees()->whereNotIn('id', $existingCommIds)->delete();
        }
        foreach ($this->committees as $i => $comm) {
            if (empty($comm['name'])) continue;
            $commData = [
                'conference_id' => $conference->id,
                'name' => $comm['name'],
                'title' => $comm['title'] ?? null,
                'institution' => $comm['institution'] ?? null,
                'type' => $comm['type'],
                'role' => $comm['role'] ?? null,
                'sort_order' => $i,
            ];
            if (!empty($comm['id'])) {
                Committee::find($comm['id'])?->update($commData);
            } else {
                Committee::create($commData);
            }
        }

        // Save topics
        $existingTopicIds = collect($this->topics)->pluck('id')->filter()->toArray();
        if ($this->isEdit) {
            $conference->topics()->whereNotIn('id', $existingTopicIds)->delete();
        }
        foreach ($this->topics as $i => $topic) {
            if (empty($topic['name'])) continue;
            $topicData = [
                'conference_id' => $conference->id,
                'name' => $topic['name'],
                'description' => $topic['description'] ?? null,
                'code' => $topic['code'] ?? null,
                'sort_order' => $i,
            ];
            if (!empty($topic['id'])) {
                Topic::find($topic['id'])?->update($topicData);
            } else {
                Topic::create($topicData);
            }
        }

        // Save speakers
        $existingSpeakerIds = collect($this->speakers)->pluck('id')->filter()->toArray();
        if ($this->isEdit) {
            // Delete removed speakers and their photos
            $toDelete = $conference->keynoteSpeakers()->whereNotIn('id', $existingSpeakerIds)->get();
            foreach ($toDelete as $del) {
                if ($del->photo) Storage::disk('public')->delete($del->photo);
                $del->delete();
            }
        }
        foreach ($this->speakers as $i => $speaker) {
            if (empty($speaker['name'])) continue;
            $speakerData = [
                'conference_id' => $conference->id,
                'type' => $speaker['type'] ?? 'keynote_speaker',
                'name' => $speaker['name'],
                'title' => $speaker['title'] ?? null,
                'institution' => $speaker['institution'] ?? null,
                'topic' => $speaker['topic'] ?? null,
                'bio' => $speaker['bio'] ?? null,
                'sort_order' => $i,
            ];

            // Handle speaker photo upload
            if (isset($this->speakerPhotos[$i]) && $this->speakerPhotos[$i]) {
                // Delete old photo if exists
                if (!empty($speaker['id'])) {
                    $existing = KeynoteSpeaker::find($speaker['id']);
                    if ($existing && $existing->photo) {
                        Storage::disk('public')->delete($existing->photo);
                    }
                }
                $speakerData['photo'] = $this->speakerPhotos[$i]->store('speakers', 'public');
            }

            if (!empty($speaker['id'])) {
                KeynoteSpeaker::find($speaker['id'])?->update($speakerData);
            } else {
                KeynoteSpeaker::create($speakerData);
            }
        }

        // Save registration packages
        $existingPkgIds = collect($this->packages)->pluck('id')->filter()->toArray();
        if ($this->isEdit) {
            $conference->registrationPackages()->whereNotIn('id', $existingPkgIds)->delete();
        }
        foreach ($this->packages as $i => $pkg) {
            if (empty($pkg['name'])) continue;
            $featuresArray = array_filter(array_map('trim', explode("\n", $pkg['features'] ?? '')));
            $pkgData = [
                'conference_id' => $conference->id,
                'name' => $pkg['name'],
                'price' => $pkg['price'] ?? 0,
                'description' => $pkg['description'] ?? null,
                'features' => array_values($featuresArray),
                'is_featured' => (bool) ($pkg['is_featured'] ?? false),
                'is_active' => (bool) ($pkg['is_active'] ?? true),
                'sort_order' => $i,
            ];
            if (!empty($pkg['id'])) {
                RegistrationPackage::find($pkg['id'])?->update($pkgData);
            } else {
                RegistrationPackage::create($pkgData);
            }
        }

        // Save reviewers (User records with role=reviewer)
        foreach ($this->reviewers as $i => $rev) {
            if (empty($rev['name']) || empty($rev['email'])) continue;
            $revData = [
                'name' => $rev['name'],
                'email' => $rev['email'],
                'institution' => $rev['institution'] ?? null,
                'phone' => $rev['phone'] ?? null,
                'role' => 'reviewer',
            ];
            if (!empty($rev['id'])) {
                $user = User::find($rev['id']);
                if ($user) {
                    $user->update($revData);
                }
            } else {
                $revData['password'] = Hash::make('password');
                User::create($revData);
            }
        }

        // Save guideline
        $guideData = [
            'content' => $this->guideline_content ?: '',
            'max_pages' => $this->max_pages,
            'min_pages' => $this->min_pages,
            'paper_format' => $this->paper_format ?: null,
            'citation_style' => $this->citation_style ?: null,
            'additional_notes' => $this->additional_notes ?: null,
        ];

        // Save deliverable templates
        $existingTplIds = collect($this->deliverableTemplates)->pluck('id')->filter()->toArray();
        if ($this->isEdit) {
            $toDelete = $conference->deliverableTemplates()->whereNotIn('id', $existingTplIds)->get();
            foreach ($toDelete as $del) {
                Storage::disk('public')->delete($del->file_path);
                $del->delete();
            }
        }
        foreach ($this->deliverableTemplates as $i => $tpl) {
            if (empty($tpl['label'])) continue;

            $tplData = [
                'conference_id' => $conference->id,
                'type' => $tpl['type'] ?? 'other',
                'label' => $tpl['label'],
                'description' => $tpl['description'] ?? null,
                'sort_order' => $i,
            ];

            // Handle file upload
            if (isset($this->templateFiles[$i]) && $this->templateFiles[$i]) {
                // Delete old file if updating
                if (!empty($tpl['id'])) {
                    $existing = DeliverableTemplate::find($tpl['id']);
                    if ($existing) {
                        Storage::disk('public')->delete($existing->file_path);
                    }
                }
                $tplData['file_path'] = $this->templateFiles[$i]->store('deliverable-templates', 'public');
                $tplData['original_name'] = $this->templateFiles[$i]->getClientOriginalName();
            } elseif (!empty($tpl['existing_file'])) {
                $tplData['file_path'] = $tpl['existing_file'];
                $tplData['original_name'] = $tpl['original_name'] ?? basename($tpl['existing_file']);
            } else {
                // No file uploaded for new template — skip
                continue;
            }

            if (!empty($tpl['id'])) {
                DeliverableTemplate::find($tpl['id'])?->update($tplData);
            } else {
                DeliverableTemplate::create($tplData);
            }
        }
        if ($this->template_file) {
            // Delete old template
            if ($this->isEdit && $conference->guideline?->template_file) {
                Storage::disk('public')->delete($conference->guideline->template_file);
            }
            $guideData['template_file'] = $this->template_file->store('guidelines', 'public');
        }

        if ($this->guideline_content || $this->min_pages || $this->max_pages || $this->paper_format || $this->template_file) {
            $conference->guideline()->updateOrCreate(['conference_id' => $conference->id], $guideData);
        }

        // Save journal publications
        $existingJournalIds = collect($this->journals)->pluck('id')->filter()->toArray();
        if ($this->isEdit) {
            $toDeleteJournals = $conference->journalPublications()->whereNotIn('id', $existingJournalIds)->get();
            foreach ($toDeleteJournals as $del) {
                if ($del->logo) Storage::disk('public')->delete($del->logo);
                $del->delete();
            }
        }
        foreach ($this->journals as $i => $journal) {
            if (empty($journal['name'])) continue;
            $journalData = [
                'conference_id' => $conference->id,
                'name' => $journal['name'],
                'sinta_rank' => $journal['sinta_rank'] ?: null,
                'url' => $journal['url'] ?: null,
                'description' => $journal['description'] ?: null,
                'is_active' => (bool) ($journal['is_active'] ?? true),
                'sort_order' => $i,
            ];

            // Handle logo upload
            if (isset($this->journalLogos[$i]) && $this->journalLogos[$i]) {
                if (!empty($journal['id'])) {
                    $existing = JournalPublication::find($journal['id']);
                    if ($existing && $existing->logo) {
                        Storage::disk('public')->delete($existing->logo);
                    }
                }
                $journalData['logo'] = $this->journalLogos[$i]->store('journal-logos', 'public');
            } elseif (!empty($journal['existing_logo'])) {
                $journalData['logo'] = $journal['existing_logo'];
            }

            if (!empty($journal['id'])) {
                JournalPublication::find($journal['id'])?->update($journalData);
            } else {
                JournalPublication::create($journalData);
            }
        }

        session()->flash('success', $this->isEdit ? 'Kegiatan berhasil diperbarui.' : 'Kegiatan berhasil dibuat.');
        return redirect()->route('admin.conferences');
    }

    public function render()
    {
        return view('livewire.admin.conference-form')
            ->layout('layouts.app');
    }
}
