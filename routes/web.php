<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetupController;
use App\Livewire\Author\SubmitPaper;
use App\Livewire\Author\PaperList as AuthorPaperList;
use App\Livewire\Author\PaperDetail as AuthorPaperDetail;
use App\Livewire\Author\PaymentUpload;
use App\Livewire\Author\DeliverableUpload;
use App\Livewire\Author\LoaList;
use App\Livewire\Admin\PaperManagement;
use App\Livewire\Admin\PaperDetail as AdminPaperDetail;
use App\Livewire\Admin\PaymentList;
use App\Livewire\Admin\ConferenceList;
use App\Livewire\Admin\ConferenceForm;
use App\Livewire\Admin\NewsList;
use App\Livewire\Admin\NewsForm;
use App\Livewire\Admin\AnnouncementList;
use App\Livewire\Admin\AnnouncementForm;
use App\Livewire\Admin\GeneralSettings;
use App\Livewire\Admin\EmailSettings;
use App\Livewire\Admin\SliderList;
use App\Livewire\Admin\SliderForm;
use App\Livewire\Admin\ThemeSettings;
use App\Livewire\Admin\MenuManager;
use App\Livewire\Admin\SupporterManager;
use App\Livewire\Admin\UsersRoles;
use App\Livewire\Admin\PageList;
use App\Livewire\Admin\PageForm;
use App\Livewire\Reviewer\ReviewList;
use App\Livewire\Reviewer\ReviewForm;
use App\Livewire\Author\Helpdesk;
use App\Livewire\Author\HelpdeskDetail as AuthorHelpdeskDetail;
use App\Livewire\Admin\HelpdeskManagement;
use App\Livewire\Admin\HelpdeskDetail as AdminHelpdeskDetail;
use App\Livewire\Profile;
use App\Livewire\Participant\PaymentProof;
use App\Livewire\Participant\ParticipantInfo;
use App\Livewire\Participant\MaterialList as ParticipantMaterialList;
use App\Http\Controllers\NotificationController;
use App\Helpers\Template;

Route::get('/', function () {
    $sliders = \App\Models\Slider::active()->get();
    $activeConference = \App\Models\Conference::active()->published()
        ->with(['importantDates', 'keynoteSpeakers', 'topics', 'committees', 'registrationPackages'])
        ->first();
    $latestNews = \App\Models\News::published()->latest('published_at')->take(3)->get();
    $announcements = \App\Models\Announcement::published()->forAudience('web')->latest('published_at')->take(3)->get();
    $headerMenus = \App\Models\Menu::getTree('header');
    $footerMenus = \App\Models\Menu::getTree('footer');
    $supporters = \App\Models\Supporter::active()->ordered()->get();
    return view(Template::view('welcome'), compact('sliders', 'activeConference', 'latestNews', 'announcements', 'headerMenus', 'footerMenus', 'supporters'));
});

// ─── Public Document Verification Routes ───
Route::get('/verify-loa/{code}', function ($code) {
    $paper = \App\Models\Paper::where('loa_number', $code)
        ->with(['user', 'conference'])
        ->first();
    
    return view('verify.loa', compact('paper', 'code'));
})->name('verify-loa');

Route::get('/verify-certificate/{code}', function ($code) {
    return view('verify.certificate', compact('code'));
})->name('verify-certificate');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ─── Helpdesk Routes (all authenticated users) ───
    Route::get('/helpdesk', Helpdesk::class)->name('helpdesk');
    Route::get('/helpdesk/{ticket}', AuthorHelpdeskDetail::class)->name('helpdesk.detail');

    // ─── Profile Route ───
    Route::get('/profile', Profile::class)->name('profile');

    // ─── Notifications API Routes ───
    Route::prefix('api/notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    });

    // ─── Participant Routes ───
    Route::middleware(['role:participant'])->prefix('participant')->group(function () {
        Route::get('/payment', PaymentProof::class)->name('participant.payment');
        Route::get('/info', ParticipantInfo::class)->name('participant.info');
        Route::get('/materials', ParticipantMaterialList::class)->name('participant.materials');
    });

    // ─── Author Routes ───
    Route::middleware(['role:author'])->prefix('author')->group(function () {
        Route::get('/papers', AuthorPaperList::class)->name('author.papers');
        Route::get('/papers/submit', SubmitPaper::class)->name('author.submit');
        Route::get('/papers/{paper}', AuthorPaperDetail::class)->name('author.paper.detail');
        Route::get('/papers/{paper}/payment', PaymentUpload::class)->name('author.paper.payment');
        Route::get('/papers/{paper}/deliverables', DeliverableUpload::class)->name('author.paper.deliverables');
        Route::get('/loa', LoaList::class)->name('author.loa');
    });

    // ─── Reviewer Routes ───
    Route::middleware(['role:reviewer'])->prefix('reviewer')->group(function () {
        Route::get('/reviews', ReviewList::class)->name('reviewer.reviews');
        Route::get('/reviews/{review}', ReviewForm::class)->name('reviewer.review.form');
    });

    // ─── Admin/Editor Shared Routes (Papers, Payments, Conferences) ───
    Route::middleware(['role:admin,editor'])->prefix('admin')->group(function () {
        Route::get('/papers', PaperManagement::class)->name('admin.papers');
        Route::get('/papers/{paper}', AdminPaperDetail::class)->name('admin.paper.detail');
        Route::get('/payments', PaymentList::class)->name('admin.payments');
        Route::get('/materials', \App\Livewire\Admin\MaterialManager::class)->name('admin.materials');

        // Kegiatan Prosiding
        Route::get('/conferences', ConferenceList::class)->name('admin.conferences');
        Route::get('/conferences/create', ConferenceForm::class)->name('admin.conferences.create');
        Route::get('/conferences/{conference}/edit', ConferenceForm::class)->name('admin.conferences.edit');
    });

    // ─── Admin Only Routes (Content, Users, Settings) ───
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // Helpdesk Management
        Route::get('/helpdesk', HelpdeskManagement::class)->name('admin.helpdesk');
        Route::get('/helpdesk/{ticket}', AdminHelpdeskDetail::class)->name('admin.helpdesk.detail');

        // Berita
        Route::get('/news', NewsList::class)->name('admin.news');
        Route::get('/news/create', NewsForm::class)->name('admin.news.create');
        Route::get('/news/{news}/edit', NewsForm::class)->name('admin.news.edit');

        // Pengumuman
        Route::get('/announcements', AnnouncementList::class)->name('admin.announcements');
        Route::get('/announcements/create', AnnouncementForm::class)->name('admin.announcements.create');
        Route::get('/announcements/{announcement}/edit', AnnouncementForm::class)->name('admin.announcements.edit');

        // Quick Announcement from Dashboard
        Route::post('/announcements/quick-create', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'type' => 'required|in:info,warning,success,danger,deadline,result',
                'priority' => 'required|in:low,normal,high,urgent',
                'audience' => 'required|in:all,author,reviewer,editor,admin,participant',
                'status' => 'required|in:draft,published',
            ]);

            $validated['created_by'] = auth()->id();
            if ($validated['status'] === 'published') {
                $validated['published_at'] = now();
            }

            \App\Models\Announcement::create($validated);

            return response()->json(['success' => true]);
        })->name('admin.announcements.quick-create');

        // Slider
        Route::get('/sliders', SliderList::class)->name('admin.sliders');
        Route::get('/sliders/create', SliderForm::class)->name('admin.sliders.create');
        Route::get('/sliders/{slider}/edit', SliderForm::class)->name('admin.sliders.edit');

        // Menu
        Route::get('/menus', MenuManager::class)->name('admin.menus');

        // Halaman Dinamis
        Route::get('/pages', PageList::class)->name('admin.pages');
        Route::get('/pages/create', PageForm::class)->name('admin.pages.create');
        Route::get('/pages/{page}/edit', PageForm::class)->name('admin.pages.edit');

        // Supporter
        Route::get('/supporters', SupporterManager::class)->name('admin.supporters');

        // Users & Roles
        Route::get('/users-roles', UsersRoles::class)->name('admin.users-roles');

        // Settings
        Route::get('/settings/general', GeneralSettings::class)->name('admin.settings.general');
        Route::get('/settings/email', EmailSettings::class)->name('admin.settings.email');
        Route::get('/settings/theme', ThemeSettings::class)->name('admin.settings.theme');

        // Impersonate User
        Route::post('/impersonate/{user}', function (\App\Models\User $user) {
            if (!auth()->user()->isAdmin()) abort(403);
            session()->put('impersonating_from', auth()->id());
            auth()->login($user);
            return redirect('/dashboard')->with('info', 'Anda sekarang login sebagai ' . $user->name);
        })->name('admin.impersonate');
    });

    // ─── Stop Impersonate (accessible to any authenticated user with session) ───
    Route::post('/admin/stop-impersonate', function () {
        $originalId = session()->pull('impersonating_from');
        if ($originalId) {
            auth()->login(\App\Models\User::findOrFail($originalId));
            return redirect('/dashboard')->with('info', 'Kembali ke akun admin.');
        }
        return redirect('/dashboard');
    })->name('admin.stop-impersonate');
});

// ─── Public Proceedings (Published Papers — grouped by Conference) ───
Route::get('/publikasi', function () {
    // Current / latest published conference with completed papers
    $currentConference = \App\Models\Conference::where('status', 'published')
        ->withCount(['papers as completed_papers_count' => fn($q) => $q->where('status', 'completed')])
        ->having('completed_papers_count', '>', 0)
        ->latest('start_date')
        ->first();

    $currentPapers = collect();
    if ($currentConference) {
        $currentPapers = \App\Models\Paper::where('status', 'completed')
            ->where('conference_id', $currentConference->id)
            ->with(['user', 'files'])
            ->latest('accepted_at')
            ->get();
    }

    // Past conferences that also have completed papers
    $pastConferences = \App\Models\Conference::where('status', 'published')
        ->when($currentConference, fn($q) => $q->where('id', '!=', $currentConference->id))
        ->withCount(['papers as completed_papers_count' => fn($q) => $q->where('status', 'completed')])
        ->having('completed_papers_count', '>', 0)
        ->latest('start_date')
        ->get();

    // Papers without a conference (legacy / unassigned)
    $unassignedPapers = \App\Models\Paper::where('status', 'completed')
        ->whereNull('conference_id')
        ->with(['user', 'files'])
        ->latest('accepted_at')
        ->get();

    $totalPapers = \App\Models\Paper::where('status', 'completed')->count();
    $totalConferences = \App\Models\Conference::where('status', 'published')
        ->withCount(['papers as completed_papers_count' => fn($q) => $q->where('status', 'completed')])
        ->having('completed_papers_count', '>', 0)
        ->count();

    $siteName = \App\Models\Setting::getValue('site_name', 'Prosiding LPKD-APJI');
    $siteTagline = \App\Models\Setting::getValue('site_tagline');
    $siteLogo = \App\Models\Setting::getValue('site_logo');
    $footerText = \App\Models\Setting::getValue('footer_text');
    $poweredBy = \App\Models\Setting::getValue('powered_by', 'Powered by Laravel');
    $headerMenus = \App\Models\Menu::getTree('header');

    return view(Template::view('proceedings'), compact(
        'currentConference', 'currentPapers', 'pastConferences', 'unassignedPapers',
        'totalPapers', 'totalConferences',
        'siteName', 'siteTagline', 'siteLogo', 'footerText', 'poweredBy', 'headerMenus'
    ));
})->name('proceedings');

// ─── Public Proceedings — Single Conference Detail ───
Route::get('/publikasi/{conference}', function (\App\Models\Conference $conference) {
    if ($conference->status !== 'published') abort(404);

    $search = request('search');
    $topic = request('topic');
    $sort = request('sort', 'newest');

    $query = \App\Models\Paper::where('status', 'completed')
        ->where('conference_id', $conference->id)
        ->with(['user', 'files', 'deliverables']);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('keywords', 'like', "%{$search}%")
              ->orWhere('abstract', 'like', "%{$search}%")
              ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
        });
    }
    if ($topic) {
        $query->where('topic', $topic);
    }

    $query->when($sort === 'oldest', fn($q) => $q->oldest('accepted_at'))
          ->when($sort === 'title', fn($q) => $q->orderBy('title'))
          ->when($sort === 'newest' || !$sort, fn($q) => $q->latest('accepted_at'));

    $papers = $query->paginate(20);

    $totalPapers = \App\Models\Paper::where('status', 'completed')->where('conference_id', $conference->id)->count();
    $totalAuthors = \App\Models\Paper::where('status', 'completed')->where('conference_id', $conference->id)->distinct('user_id')->count('user_id');
    $topics = \App\Models\Paper::where('status', 'completed')->where('conference_id', $conference->id)->whereNotNull('topic')->distinct()->pluck('topic')->sort()->values();

    $siteName = \App\Models\Setting::getValue('site_name', 'Prosiding LPKD-APJI');
    $siteTagline = \App\Models\Setting::getValue('site_tagline');
    $siteLogo = \App\Models\Setting::getValue('site_logo');
    $footerText = \App\Models\Setting::getValue('footer_text');
    $poweredBy = \App\Models\Setting::getValue('powered_by', 'Powered by Laravel');
    $headerMenus = \App\Models\Menu::getTree('header');

    return view(Template::view('proceedings-detail'), compact(
        'conference', 'papers', 'totalPapers', 'totalAuthors', 'topics',
        'siteName', 'siteTagline', 'siteLogo', 'footerText', 'poweredBy', 'headerMenus'
    ));
})->name('proceedings.show');

// ─── Public Archive (Past Conferences) ───
Route::get('/arsip', function () {
    $conferences = \App\Models\Conference::where('status', 'published')
        ->where('is_active', false)
        ->with(['importantDates', 'topics', 'keynoteSpeakers', 'committees'])
        ->latest('start_date')
        ->paginate(12);
    $siteName = \App\Models\Setting::getValue('site_name', 'Prosiding LPKD-APJI');
    $siteTagline = \App\Models\Setting::getValue('site_tagline');
    $siteLogo = \App\Models\Setting::getValue('site_logo');
    $footerText = \App\Models\Setting::getValue('footer_text');
    $poweredBy = \App\Models\Setting::getValue('powered_by', 'Powered by Laravel');
    $headerMenus = \App\Models\Menu::getTree('header');
    return view(Template::view('archive'), compact('conferences', 'siteName', 'siteTagline', 'siteLogo', 'footerText', 'poweredBy', 'headerMenus'));
})->name('archive');

// ─── Public Archive — Single Conference Detail ───
Route::get('/arsip/{conference}', function (\App\Models\Conference $conference) {
    if ($conference->status !== 'published') abort(404);

    $conference->load(['importantDates', 'topics', 'keynoteSpeakers', 'committees', 'registrationPackages', 'guideline']);

    $completedPapersCount = $conference->papers()->where('status', 'completed')->count();

    $siteName = \App\Models\Setting::getValue('site_name', 'Prosiding LPKD-APJI');
    $siteTagline = \App\Models\Setting::getValue('site_tagline');
    $siteLogo = \App\Models\Setting::getValue('site_logo');
    $footerText = \App\Models\Setting::getValue('footer_text');
    $poweredBy = \App\Models\Setting::getValue('powered_by', 'Powered by Laravel');
    $headerMenus = \App\Models\Menu::getTree('header');

    return view(Template::view('archive-detail'), compact(
        'conference', 'completedPapersCount',
        'siteName', 'siteTagline', 'siteLogo', 'footerText', 'poweredBy', 'headerMenus'
    ));
})->name('archive.show');

// ─── Public News Detail ───
Route::get('/news/{news:slug}', function (\App\Models\News $news) {
    if ($news->status !== 'published') abort(404);
    $news->increment('views_count');
    $news->load('author');
    $relatedNews = \App\Models\News::published()
        ->where('id', '!=', $news->id)
        ->where('category', $news->category)
        ->latest('published_at')->take(3)->get();
    $latestNews = \App\Models\News::published()
        ->where('id', '!=', $news->id)
        ->latest('published_at')->take(5)->get();
    $siteName = \App\Models\Setting::getValue('site_name', 'Prosiding LPKD-APJI');
    $siteTagline = \App\Models\Setting::getValue('site_tagline');
    $siteLogo = \App\Models\Setting::getValue('site_logo');
    $footerText = \App\Models\Setting::getValue('footer_text');
    $poweredBy = \App\Models\Setting::getValue('powered_by', 'Powered by Laravel');
    return view(Template::view('news-detail'), compact('news', 'relatedNews', 'latestNews', 'siteName', 'siteTagline', 'siteLogo', 'footerText', 'poweredBy'));
})->name('news.detail');

// ─── Public Dynamic Page ───
Route::get('/page/{page:slug}', function (\App\Models\Page $page) {
    if ($page->status !== 'published') abort(404);
    $page->increment('views_count');
    $relatedPages = \App\Models\Page::published()
        ->where('id', '!=', $page->id)
        ->ordered()->take(10)->get();
    return view(Template::view('page'), compact('page', 'relatedPages'));
})->name('page.show');

// ─── Setup Installer (cPanel deployment, tanpa terminal) ───────────────────
Route::prefix('setup')->name('setup.')->group(function () {
    Route::get  ('/',            [SetupController::class, 'index'])       ->name('index');
    Route::post ('/auth',        [SetupController::class, 'auth'])        ->name('auth');
    Route::post ('/requirements',[SetupController::class, 'requirements'])->name('requirements');
    Route::post ('/save-env',    [SetupController::class, 'saveEnv'])     ->name('save-env');
    Route::post ('/generate-key',[SetupController::class, 'generateKey']) ->name('generate-key');
    Route::post ('/test-db',     [SetupController::class, 'testDb'])      ->name('test-db');
    Route::post ('/migrate',     [SetupController::class, 'migrate'])     ->name('migrate');
    Route::post ('/seed',        [SetupController::class, 'seed'])        ->name('seed');
    Route::post ('/finalize',    [SetupController::class, 'finalize'])    ->name('finalize');
    Route::post ('/reset-lock',  [SetupController::class, 'resetLock'])   ->name('reset-lock');
});
