<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class UsersRoles extends Component
{
    use WithPagination;

    // ─── Tab State ───
    public string $activeTab = 'users';

    // ─── Users Tab ───
    public string $userSearch = '';
    public string $userRoleFilter = '';
    public bool $showUserModal = false;
    public bool $showUserDetailModal = false;
    public ?object $detailUser = null;
    public bool $editingUser = false;
    public ?int $editUserId = null;
    public string $userName = '';
    public string $userEmail = '';
    public string $userPassword = '';
    public string $userRole = 'author';
    public string $userInstitution = '';
    public string $userPhone = '';
    public string $userGender = '';
    public string $userCountry = '';
    public string $userResearchInterest = '';
    public string $userOtherInfo = '';
    public array $userRoles = [];

    // ─── Assign Role Modal ───
    public bool $showAssignRoleModal = false;
    public ?int $assignUserId = null;
    public string $assignUserName = '';
    public array $assignRoles = [];

    // ─── Roles Tab ───
    public string $roleSearch = '';
    public bool $showRoleModal = false;
    public bool $editingRole = false;
    public ?int $editRoleId = null;
    public string $roleName = '';
    public string $rolePermissionLevel = 'author';
    public bool $roleCanSubmission = false;
    public bool $roleCanReview = false;
    public bool $roleCanCopyediting = false;
    public bool $roleCanProduction = false;
    public string $roleDescription = '';

    // ─── Delete Confirmation ───
    public bool $showDeleteModal = false;
    public string $deleteType = '';
    public ?int $deleteId = null;
    public string $deleteName = '';

    protected $queryString = ['activeTab'];

    public function updatingUserSearch()
    {
        $this->resetPage('usersPage');
    }

    public function updatingUserRoleFilter()
    {
        $this->resetPage('usersPage');
    }

    public function updatingRoleSearch()
    {
        $this->resetPage('rolesPage');
    }

    // ════════════════════════════════════════
    //  USERS TAB
    // ════════════════════════════════════════

    public function openCreateUser()
    {
        $this->resetUserForm();
        $this->editingUser = false;
        $this->showUserModal = true;
    }

    public function openUserDetail(int $id)
    {
        $this->detailUser = User::with('roles')->findOrFail($id);
        $this->showUserDetailModal = true;
    }

    public function closeUserDetail()
    {
        $this->showUserDetailModal = false;
        $this->detailUser = null;
    }

    public function openEditUser(int $id)
    {
        $user = User::findOrFail($id);
        $this->editUserId = $user->id;
        $this->userName = $user->name;
        $this->userEmail = $user->email;
        $this->userPassword = '';
        $this->userRole = $user->role;
        $this->userInstitution = $user->institution ?? '';
        $this->userPhone = $user->phone ?? '';
        $this->userGender = $user->gender ?? '';
        $this->userCountry = $user->country ?? '';
        $this->userResearchInterest = $user->research_interest ?? '';
        $this->userOtherInfo = $user->other_info ?? '';
        $this->editingUser = true;
        $this->showUserModal = true;
    }

    public function saveUser()
    {
        $rules = [
            'userName'  => 'required|string|max:255',
            'userEmail' => 'required|email|max:255|unique:users,email,' . ($this->editUserId ?? 'NULL'),
            'userRole'  => 'required|in:admin,editor,reviewer,author,participant',
        ];

        if (!$this->editingUser) {
            $rules['userPassword'] = 'required|string|min:6';
        }

        $this->validate($rules);

        $data = [
            'name'              => $this->userName,
            'email'             => $this->userEmail,
            'role'              => $this->userRole,
            'institution'       => $this->userInstitution ?: null,
            'phone'             => $this->userPhone ?: null,
            'gender'            => $this->userGender ?: null,
            'country'           => $this->userCountry ?: null,
            'research_interest' => $this->userResearchInterest ?: null,
            'other_info'        => $this->userOtherInfo ?: null,
        ];

        if ($this->userPassword) {
            $data['password'] = Hash::make($this->userPassword);
        }

        if ($this->editingUser && $this->editUserId) {
            $user = User::findOrFail($this->editUserId);
            $user->update($data);
            session()->flash('success', 'User berhasil diperbarui.');
        } else {
            User::create($data);
            session()->flash('success', 'User berhasil dibuat.');
        }

        $this->showUserModal = false;
        $this->resetUserForm();
    }

    // ─── Assign Roles to User ───

    public function openAssignRole(int $userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        $this->assignUserId = $user->id;
        $this->assignUserName = $user->name;
        $this->assignRoles = $user->roles->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->showAssignRoleModal = true;
    }

    public function saveAssignedRoles()
    {
        $user = User::findOrFail($this->assignUserId);
        $user->roles()->sync(array_map('intval', $this->assignRoles));
        session()->flash('success', "Role untuk {$user->name} berhasil diperbarui.");
        $this->showAssignRoleModal = false;
    }

    public function closeAssignRoleModal()
    {
        $this->showAssignRoleModal = false;
    }

    private function resetUserForm()
    {
        $this->editUserId = null;
        $this->userName = '';
        $this->userEmail = '';
        $this->userPassword = '';
        $this->userRole = 'author';
        $this->userInstitution = '';
        $this->userPhone = '';
        $this->userGender = '';
        $this->userCountry = '';
        $this->userResearchInterest = '';
        $this->userOtherInfo = '';
    }

    // ════════════════════════════════════════
    //  ROLES TAB
    // ════════════════════════════════════════

    public function openCreateRole()
    {
        $this->resetRoleForm();
        $this->editingRole = false;
        $this->showRoleModal = true;
    }

    public function openEditRole(int $id)
    {
        $role = Role::findOrFail($id);
        $this->editRoleId = $role->id;
        $this->roleName = $role->name;
        $this->rolePermissionLevel = $role->permission_level;
        $this->roleCanSubmission = $role->can_submission;
        $this->roleCanReview = $role->can_review;
        $this->roleCanCopyediting = $role->can_copyediting;
        $this->roleCanProduction = $role->can_production;
        $this->roleDescription = $role->description ?? '';
        $this->editingRole = true;
        $this->showRoleModal = true;
    }

    public function saveRole()
    {
        $this->validate([
            'roleName'            => 'required|string|max:255',
            'rolePermissionLevel' => 'required|in:admin,manager,editor,assistant,reviewer,author',
        ]);

        $data = [
            'name'             => $this->roleName,
            'slug'             => Str::slug($this->roleName),
            'permission_level' => $this->rolePermissionLevel,
            'can_submission'   => $this->roleCanSubmission,
            'can_review'       => $this->roleCanReview,
            'can_copyediting'  => $this->roleCanCopyediting,
            'can_production'   => $this->roleCanProduction,
            'description'      => $this->roleDescription ?: null,
        ];

        if ($this->editingRole && $this->editRoleId) {
            $role = Role::findOrFail($this->editRoleId);
            // Ensure slug uniqueness on update
            $data['slug'] = Str::slug($this->roleName);
            if (Role::where('slug', $data['slug'])->where('id', '!=', $role->id)->exists()) {
                $data['slug'] .= '-' . $role->id;
            }
            $role->update($data);
            session()->flash('success', 'Role berhasil diperbarui.');
        } else {
            // Ensure slug uniqueness on create
            $slug = Str::slug($this->roleName);
            $counter = 1;
            while (Role::where('slug', $slug)->exists()) {
                $slug = Str::slug($this->roleName) . '-' . $counter++;
            }
            $data['slug'] = $slug;
            $data['sort_order'] = Role::max('sort_order') + 1;
            Role::create($data);
            session()->flash('success', 'Role berhasil dibuat.');
        }

        $this->showRoleModal = false;
        $this->resetRoleForm();
    }

    public function togglePermission(int $roleId, string $permission)
    {
        $role = Role::findOrFail($roleId);
        $column = "can_{$permission}";
        $role->update([$column => !$role->{$column}]);
    }

    private function resetRoleForm()
    {
        $this->editRoleId = null;
        $this->roleName = '';
        $this->rolePermissionLevel = 'author';
        $this->roleCanSubmission = false;
        $this->roleCanReview = false;
        $this->roleCanCopyediting = false;
        $this->roleCanProduction = false;
        $this->roleDescription = '';
    }

    // ════════════════════════════════════════
    //  DELETE
    // ════════════════════════════════════════

    public function confirmDelete(string $type, int $id)
    {
        $this->deleteType = $type;
        $this->deleteId = $id;
        $this->deleteName = $type === 'user'
            ? User::find($id)?->name ?? ''
            : Role::find($id)?->name ?? '';
        $this->showDeleteModal = true;
    }

    public function executeDelete()
    {
        if ($this->deleteType === 'user') {
            User::findOrFail($this->deleteId)->delete();
            session()->flash('success', 'User berhasil dihapus.');
        } elseif ($this->deleteType === 'role') {
            $role = Role::findOrFail($this->deleteId);
            $role->users()->detach();
            $role->delete();
            session()->flash('success', 'Role berhasil dihapus.');
        }
        $this->showDeleteModal = false;
    }

    public function closeModal()
    {
        $this->showUserModal = false;
        $this->showRoleModal = false;
        $this->showDeleteModal = false;
        $this->showAssignRoleModal = false;
        $this->showUserDetailModal = false;
        $this->detailUser = null;
    }

    // ════════════════════════════════════════
    //  RENDER
    // ════════════════════════════════════════

    public function render()
    {
        // Users query
        $usersQuery = User::query()
            ->when($this->userSearch, function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->userSearch}%")
                      ->orWhere('email', 'like', "%{$this->userSearch}%")
                      ->orWhere('institution', 'like', "%{$this->userSearch}%");
                });
            })
            ->when($this->userRoleFilter, function ($q) {
                $q->where('role', $this->userRoleFilter);
            })
            ->latest();

        $users = $usersQuery->paginate(15, pageName: 'usersPage');

        // Load user roles
        $users->load('roles');

        // Roles query
        $rolesQuery = Role::query()
            ->when($this->roleSearch, function ($q) {
                $q->where('name', 'like', "%{$this->roleSearch}%");
            })
            ->withCount('users')
            ->ordered();

        $roles = $rolesQuery->get();

        // All roles for assign modal
        $allRoles = Role::ordered()->get();

        // Permission levels
        $permissionLevels = Role::permissionLevels();

        return view('livewire.admin.users-roles', compact(
            'users', 'roles', 'allRoles', 'permissionLevels'
        ))->layout('layouts.app');
    }
}
