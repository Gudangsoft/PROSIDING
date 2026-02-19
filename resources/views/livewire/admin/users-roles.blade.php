<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Users & Roles</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola pengguna dan hak akses role pada sistem prosiding.</p>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Tabs --}}
    <div class="border-b border-gray-200 mb-6">
        <nav class="flex gap-6">
            <button wire:click="$set('activeTab', 'users')"
                    class="pb-3 text-sm font-medium border-b-2 transition {{ $activeTab === 'users' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Users
            </button>
            <button wire:click="$set('activeTab', 'roles')"
                    class="pb-3 text-sm font-medium border-b-2 transition {{ $activeTab === 'roles' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Roles
            </button>
        </nav>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- USERS TAB --}}
    {{-- ═══════════════════════════════════════════ --}}
    @if($activeTab === 'users')
    <div>
        {{-- Toolbar --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                <div class="relative flex-1 max-w-sm">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="userSearch" type="text" placeholder="Cari nama, email, institusi..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <select wire:model.live="userRoleFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="reviewer">Reviewer</option>
                    <option value="author">Author</option>
                    <option value="participant">Partisipan</option>
                </select>
            </div>
            <button wire:click="openCreateUser"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition shrink-0 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah User
            </button>
        </div>

        {{-- Users Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Email</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Base Role</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Assigned Roles</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Institusi</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" class="w-8 h-8 rounded-full object-cover shrink-0">
                                    @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                        @if($user->phone)
                                        <p class="text-xs text-gray-400">{{ $user->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                    @if($user->role === 'admin') bg-red-100 text-red-700
                                    @elseif($user->role === 'editor') bg-purple-100 text-purple-700
                                    @elseif($user->role === 'reviewer') bg-indigo-100 text-indigo-700
                                    @elseif($user->role === 'participant') bg-teal-100 text-teal-700
                                    @else bg-blue-100 text-blue-700
                                    @endif">
                                    {{ $user->role === 'participant' ? 'Partisipan' : ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $role->name }}
                                    </span>
                                    @empty
                                    <span class="text-xs text-gray-400 italic">Belum ada</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->institution ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    @if(Auth::user()->isAdmin() && $user->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.impersonate', $user) }}" class="inline">
                                        @csrf
                                        <button type="submit" title="Login As {{ $user->name }}"
                                                class="p-1.5 rounded-lg text-yellow-600 hover:bg-yellow-50 transition cursor-pointer"
                                                onclick="return confirm('Login sebagai {{ $user->name }}?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                    <button wire:click="openUserDetail({{ $user->id }})" title="Lihat Detail"
                                            class="p-1.5 rounded-lg text-gray-600 hover:bg-gray-100 transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button wire:click="openAssignRole({{ $user->id }})" title="Assign Role"
                                            class="p-1.5 rounded-lg text-green-600 hover:bg-green-50 transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </button>
                                    <button wire:click="openEditUser({{ $user->id }})" title="Edit User"
                                            class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="confirmDelete('user', {{ $user->id }})" title="Hapus User"
                                            class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <p>Tidak ada user ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════ --}}
    {{-- ROLES TAB --}}
    {{-- ═══════════════════════════════════════════ --}}
    @if($activeTab === 'roles')
    <div>
        {{-- Toolbar --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div class="relative flex-1 max-w-sm">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="roleSearch" type="text" placeholder="Cari role..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button wire:click="openCreateRole"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition shrink-0 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Buat Role Baru
            </button>
        </div>

        {{-- Current Roles --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Current Roles</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold text-gray-600 w-48">Role Name</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-600 w-40">Permission Level</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-600 w-28">Submission</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-600 w-28">Review</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-600 w-28">Copyediting</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-600 w-28">Production</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-600 w-20">Users</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-600 w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($roles as $role)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4">
                                <div>
                                    <p class="font-medium text-blue-700">{{ $role->name }}</p>
                                    @if($role->description)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $role->description }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if(in_array($role->permission_level, ['admin', 'manager'])) bg-amber-100 text-amber-800
                                    @elseif($role->permission_level === 'editor') bg-purple-100 text-purple-800
                                    @elseif($role->permission_level === 'assistant') bg-teal-100 text-teal-800
                                    @elseif($role->permission_level === 'reviewer') bg-indigo-100 text-indigo-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ $role->permission_level_label }}
                                </span>
                            </td>
                            {{-- Permission Checkboxes --}}
                            <td class="px-5 py-4 text-center">
                                <button wire:click="togglePermission({{ $role->id }}, 'submission')" class="cursor-pointer">
                                    @if($role->can_submission)
                                    <svg class="w-5 h-5 mx-auto text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    @else
                                    <div class="w-5 h-5 mx-auto border-2 border-gray-300 rounded"></div>
                                    @endif
                                </button>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <button wire:click="togglePermission({{ $role->id }}, 'review')" class="cursor-pointer">
                                    @if($role->can_review)
                                    <svg class="w-5 h-5 mx-auto text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    @else
                                    <div class="w-5 h-5 mx-auto border-2 border-gray-300 rounded"></div>
                                    @endif
                                </button>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <button wire:click="togglePermission({{ $role->id }}, 'copyediting')" class="cursor-pointer">
                                    @if($role->can_copyediting)
                                    <svg class="w-5 h-5 mx-auto text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    @else
                                    <div class="w-5 h-5 mx-auto border-2 border-gray-300 rounded"></div>
                                    @endif
                                </button>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <button wire:click="togglePermission({{ $role->id }}, 'production')" class="cursor-pointer">
                                    @if($role->can_production)
                                    <svg class="w-5 h-5 mx-auto text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    @else
                                    <div class="w-5 h-5 mx-auto border-2 border-gray-300 rounded"></div>
                                    @endif
                                </button>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-xs font-semibold text-gray-600">
                                    {{ $role->users_count }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    <button wire:click="openEditRole({{ $role->id }})" title="Edit Role"
                                            class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="confirmDelete('role', {{ $role->id }})" title="Hapus Role"
                                            class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <p>Belum ada role. Klik "Buat Role Baru" untuk mulai.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Permission Legend --}}
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-blue-800 mb-2">Keterangan Permission Stage</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs text-blue-700">
                <div><span class="font-medium">Submission:</span> Akses ke tahap pengajuan paper</div>
                <div><span class="font-medium">Review:</span> Akses ke tahap review paper</div>
                <div><span class="font-medium">Copyediting:</span> Akses ke tahap copyedit</div>
                <div><span class="font-medium">Production:</span> Akses ke tahap produksi & publikasi</div>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════ --}}
    {{-- USER MODAL (Create/Edit) --}}
    {{-- ═══════════════════════════════════════════ --}}
    @if($showUserModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data x-init="$el.querySelector('input')?.focus()">
        <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg relative z-10 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    {{ $editingUser ? 'Edit User' : 'Tambah User Baru' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="saveUser" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input wire:model="userName" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('userName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input wire:model="userEmail" type="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('userEmail') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password {{ $editingUser ? '(kosongkan jika tidak diubah)' : '' }} <span class="{{ $editingUser ? '' : 'text-red-500' }}">{{ $editingUser ? '' : '*' }}</span>
                    </label>
                    <input wire:model="userPassword" type="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('userPassword') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Base Role <span class="text-red-500">*</span></label>
                    <select wire:model="userRole" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="admin">Admin</option>
                        <option value="editor">Editor</option>
                        <option value="reviewer">Reviewer</option>
                        <option value="author">Author</option>
                        <option value="participant">Partisipan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Institusi</label>
                    <input wire:model="userInstitution" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                    <input wire:model="userPhone" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select wire:model="userGender" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">-- Pilih --</option>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Negara</label>
                    <input wire:model="userCountry" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Research Interest</label>
                    <input wire:model="userResearchInterest" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Informasi Lainnya</label>
                    <textarea wire:model="userOtherInfo" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition cursor-pointer">
                        {{ $editingUser ? 'Simpan Perubahan' : 'Buat User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════ --}}
    {{-- ASSIGN ROLE MODAL --}}
    {{-- ═══════════════════════════════════════════ --}}
    @if($showAssignRoleModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" wire:click="closeAssignRoleModal"></div>
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md relative z-10">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    Assign Roles — {{ $assignUserName }}
                </h3>
                <button wire:click="closeAssignRoleModal" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-4">Pilih role yang ingin diberikan:</p>
                <div class="space-y-2 max-h-72 overflow-y-auto">
                    @foreach($allRoles as $role)
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                        <input type="checkbox" wire:model="assignRoles" value="{{ $role->id }}"
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $role->name }}</p>
                            <p class="text-xs text-gray-400">{{ $role->permission_level_label }}</p>
                        </div>
                        <div class="flex gap-1">
                            @if($role->can_submission) <span class="w-2 h-2 rounded-full bg-green-400" title="Submission"></span> @endif
                            @if($role->can_review) <span class="w-2 h-2 rounded-full bg-blue-400" title="Review"></span> @endif
                            @if($role->can_copyediting) <span class="w-2 h-2 rounded-full bg-yellow-400" title="Copyediting"></span> @endif
                            @if($role->can_production) <span class="w-2 h-2 rounded-full bg-purple-400" title="Production"></span> @endif
                        </div>
                    </label>
                    @endforeach
                </div>
                <div class="flex justify-end gap-3 pt-4 mt-4 border-t border-gray-100">
                    <button wire:click="closeAssignRoleModal" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition cursor-pointer">Batal</button>
                    <button wire:click="saveAssignedRoles" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition cursor-pointer">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════ --}}
    {{-- ROLE MODAL (Create/Edit) --}}
    {{-- ═══════════════════════════════════════════ --}}
    @if($showRoleModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data x-init="$el.querySelector('input')?.focus()">
        <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg relative z-10 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    {{ $editingRole ? 'Edit Role' : 'Buat Role Baru' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="saveRole" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Role <span class="text-red-500">*</span></label>
                    <input wire:model="roleName" type="text" placeholder="e.g. Journal editor, Copyeditor..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('roleName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Permission Level <span class="text-red-500">*</span></label>
                    <select wire:model="rolePermissionLevel"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach($permissionLevels as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Akses Stage Workflow</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                            <input wire:model="roleCanSubmission" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Submission</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                            <input wire:model="roleCanReview" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Review</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                            <input wire:model="roleCanCopyediting" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Copyediting</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                            <input wire:model="roleCanProduction" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Production</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea wire:model="roleDescription" rows="2" placeholder="Deskripsi singkat role ini..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition cursor-pointer">
                        {{ $editingRole ? 'Simpan Perubahan' : 'Buat Role' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════ --}}
    {{-- DELETE CONFIRMATION MODAL --}}
    {{-- ═══════════════════════════════════════════ --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm relative z-10">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.072 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Hapus {{ $deleteType === 'user' ? 'User' : 'Role' }}?</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Anda yakin ingin menghapus <strong>{{ $deleteName }}</strong>?
                    @if($deleteType === 'role') Semua user yang memiliki role ini akan kehilangan akses tersebut. @endif
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex justify-center gap-3">
                    <button wire:click="closeModal" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition cursor-pointer">Batal</button>
                    <button wire:click="executeDelete" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition cursor-pointer">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════ --}}
    {{-- USER DETAIL MODAL --}}
    {{-- ═══════════════════════════════════════════ --}}
    @if($showUserDetailModal && $detailUser)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" wire:click="closeUserDetail"></div>
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl relative z-10 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Detail User</h3>
                <button wire:click="closeUserDetail" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                {{-- User header --}}
                <div class="flex items-center gap-4 mb-6">
                    @if($detailUser->photo)
                    <img src="{{ asset('storage/' . $detailUser->photo) }}" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                    @else
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($detailUser->name, 0, 1)) }}
                    </div>
                    @endif
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">{{ $detailUser->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $detailUser->email }}</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold mt-1
                            @if($detailUser->role === 'admin') bg-red-100 text-red-700
                            @elseif($detailUser->role === 'editor') bg-purple-100 text-purple-700
                            @elseif($detailUser->role === 'reviewer') bg-indigo-100 text-indigo-700
                            @elseif($detailUser->role === 'participant') bg-teal-100 text-teal-700
                            @else bg-blue-100 text-blue-700
                            @endif">
                            {{ $detailUser->role === 'participant' ? 'Partisipan' : ucfirst($detailUser->role) }}
                        </span>
                    </div>
                </div>

                {{-- Info grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Jenis Kelamin</p>
                        <p class="text-gray-800">{{ $detailUser->gender === 'male' ? 'Laki-laki' : ($detailUser->gender === 'female' ? 'Perempuan' : '-') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Telepon</p>
                        <p class="text-gray-800">{{ $detailUser->phone ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Institusi</p>
                        <p class="text-gray-800">{{ $detailUser->institution ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Negara</p>
                        <p class="text-gray-800">{{ $detailUser->country ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 md:col-span-2">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Research Interest</p>
                        <p class="text-gray-800">{{ $detailUser->research_interest ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 md:col-span-2">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Informasi Lainnya</p>
                        <p class="text-gray-800">{{ $detailUser->other_info ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Assigned Roles</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @forelse($detailUser->roles as $role)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-700">{{ $role->name }}</span>
                            @empty
                            <span class="text-gray-400 italic">Belum ada</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Terdaftar</p>
                        <p class="text-gray-800">{{ $detailUser->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Files section --}}
                @if($detailUser->signature || $detailUser->proof_of_payment)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-3">File Upload</p>
                    <div class="flex flex-wrap gap-4">
                        @if($detailUser->proof_of_payment)
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Bukti Pembayaran</p>
                            @if(str_ends_with($detailUser->proof_of_payment, '.pdf'))
                            <a href="{{ asset('storage/' . $detailUser->proof_of_payment) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat PDF</a>
                            @else
                            <img src="{{ asset('storage/' . $detailUser->proof_of_payment) }}" class="max-h-24 rounded border cursor-pointer" onclick="window.open(this.src)">
                            @endif
                        </div>
                        @endif
                        @if($detailUser->signature)
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Tanda Tangan</p>
                            <img src="{{ asset('storage/' . $detailUser->signature) }}" class="max-h-24 rounded border">
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="flex justify-end gap-3 pt-4 mt-4 border-t border-gray-100">
                    <button wire:click="closeUserDetail" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition cursor-pointer">Tutup</button>
                    <button wire:click="closeUserDetail" wire:click.prevent="openEditUser({{ $detailUser->id }})" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition cursor-pointer">Edit User</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
