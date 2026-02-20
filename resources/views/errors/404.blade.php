<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }

        .bg-grid {
            background-image:
                linear-gradient(rgba(99,102,241,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,.06) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(-2deg); }
            50%       { transform: translateY(-18px) rotate(2deg); }
        }
        @keyframes float2 {
            0%, 100% { transform: translateY(0px) rotate(3deg); }
            50%       { transform: translateY(-12px) rotate(-3deg); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 30px 8px rgba(99,102,241,.18); }
            50%       { box-shadow: 0 0 60px 16px rgba(99,102,241,.32); }
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0; }
        }
        @keyframes slidein {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        @keyframes counter-spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(-360deg); }
        }

        .float-1 { animation: float  4s ease-in-out infinite; }
        .float-2 { animation: float2 5s ease-in-out infinite 0.8s; }
        .float-3 { animation: float  6s ease-in-out infinite 1.5s; }
        .pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
        .blink { animation: blink 1s step-end infinite; }
        .slidein-1 { animation: slidein .6s ease both .1s; }
        .slidein-2 { animation: slidein .6s ease both .25s; }
        .slidein-3 { animation: slidein .6s ease both .4s; }
        .slidein-4 { animation: slidein .6s ease both .55s; }
        .spin-ring  { animation: spin-slow 8s linear infinite; }
        .counter-ring { animation: counter-spin 6s linear infinite; }
    </style>
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center overflow-hidden relative bg-grid">

    {{-- Ambient blobs --}}
    <div class="absolute top-[-120px] left-[-120px] w-[480px] h-[480px] bg-indigo-600/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-80px] right-[-80px] w-[380px] h-[380px] bg-purple-600/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-indigo-900/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- Floating debris --}}
    <div class="float-1 absolute top-[12%] left-[8%] w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-400/20 backdrop-blur-sm"></div>
    <div class="float-2 absolute top-[20%] right-[10%] w-6 h-6 rounded-lg bg-purple-500/15 border border-purple-400/20"></div>
    <div class="float-3 absolute bottom-[18%] left-[14%] w-8 h-8 rounded-full bg-blue-500/10 border border-blue-400/20"></div>
    <div class="float-1 absolute bottom-[22%] right-[8%] w-12 h-12 rounded-2xl bg-violet-500/10 border border-violet-400/20"></div>
    <div class="float-2 absolute top-[55%] left-[4%] w-5 h-5 rounded bg-indigo-400/20"></div>
    <div class="float-3 absolute top-[40%] right-[5%] w-7 h-7 rounded-full bg-purple-400/15"></div>

    <div class="relative z-10 text-center px-6 max-w-lg w-full">

        {{-- 404 Number with rings --}}
        <div class="slidein-1 relative inline-flex items-center justify-center mb-8">
            {{-- Outer spinning ring --}}
            <div class="spin-ring absolute w-52 h-52 rounded-full border-2 border-dashed border-indigo-500/30"></div>
            {{-- Inner counter-spinning ring --}}
            <div class="counter-ring absolute w-40 h-40 rounded-full border border-dotted border-purple-500/40"></div>

            {{-- Glowing circle --}}
            <div class="pulse-glow w-32 h-32 rounded-full bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center relative">
                <span class="text-white font-black text-4xl tracking-tight leading-none select-none" style="text-shadow:0 0 20px rgba(255,255,255,.5)">404</span>
            </div>
        </div>

        {{-- Headline --}}
        <div class="slidein-2">
            <h1 class="text-white font-extrabold text-3xl sm:text-4xl mb-3 tracking-tight">
                Halaman Tidak Ditemukan
            </h1>
            <p class="text-gray-400 text-base leading-relaxed">
                Sepertinya halaman yang Anda cari telah dipindahkan,<br class="hidden sm:block"> dihapus, atau memang tidak pernah ada.
            </p>
        </div>

        {{-- Divider with code hint --}}
        <div class="slidein-3 my-6 flex items-center gap-3">
            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-700 to-transparent"></div>
            <span class="text-gray-600 text-xs font-mono bg-gray-900/60 border border-gray-700/50 px-3 py-1 rounded-full">
                HTTP 404
                <span class="blink ml-0.5 text-indigo-400">|</span>
            </span>
            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-700 to-transparent"></div>
        </div>

        {{-- URL display --}}
        <div class="slidein-3 mb-6">
            <div class="inline-flex items-center gap-2 bg-gray-900/60 border border-gray-700/60 rounded-lg px-4 py-2 text-sm font-mono text-gray-500 max-w-full overflow-hidden">
                <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <span class="truncate text-gray-400">{{ request()->path() }}</span>
            </div>
        </div>

        {{-- Action buttons --}}
        <div class="slidein-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            @auth
                @php
                    $user = Auth::user();
                    if ($user->isAdmin() || $user->isEditor()) {
                        $homeRoute = route('admin.conferences');
                        $homeLabel = 'Ke Dashboard Admin';
                    } elseif ($user->isAuthor()) {
                        $homeRoute = route('author.papers');
                        $homeLabel = 'Ke Dashboard Author';
                    } elseif ($user->isParticipant()) {
                        $homeRoute = route('participant.payment');
                        $homeLabel = 'Ke Dashboard';
                    } elseif ($user->isReviewer()) {
                        $homeRoute = route('reviewer.list');
                        $homeLabel = 'Ke Dashboard Reviewer';
                    } else {
                        $homeRoute = route('dashboard');
                        $homeLabel = 'Ke Dashboard';
                    }
                @endphp
                <a href="{{ $homeRoute }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl transition shadow-lg shadow-indigo-900/30 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ $homeLabel }}
                </a>
                <button onclick="history.back()"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 font-semibold rounded-xl transition border border-gray-700 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </button>
            @else
                <a href="{{ url('/') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl transition shadow-lg shadow-indigo-900/30 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Kembali ke Beranda
                </a>
                <button onclick="history.back()"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 font-semibold rounded-xl transition border border-gray-700 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </button>
            @endauth
        </div>

        {{-- Footer --}}
        <p class="slidein-4 mt-10 text-xs text-gray-700">
            &copy; {{ date('Y') }} {{ \App\Models\Setting::getValue('site_name', 'Prosiding LPKD-APJI') }}
        </p>
    </div>
</body>
</html>
