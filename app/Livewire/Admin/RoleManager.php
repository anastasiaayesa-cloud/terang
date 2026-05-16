<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManager extends Component
{
    public $roleName = '';

    public $selectedPermissions = [];

    public $isEditMode = false;

    public $editingRoleId = null;

    public $roles = [];

    public $groupedPermissions = [];

    protected $menuLabels = [
        'kepegawaian' => 'Kepegawaian',
        'dokumen-perencanaan' => 'Dokumen Perencanaan',
        'perencanaan' => 'Perencanaan',
        'bukti-pengeluaran' => 'Bukti Pengeluaran',
        'usulan-pembayaran' => 'Usulan Pembayaran',
        'keuangan' => 'Keuangan',
        'usulan' => 'Usulan',
        'persuratan' => 'Persuratan',
        'usulan-pegawai' => 'Usulan Pegawai',
        'daftar-kegiatans' => 'Daftar Kegiatan',
        'instansi' => 'Instansi',
        'laporan-kegiatans' => 'Laporan Kegiatan',
        'role-management' => 'Manajemen Role',
    ];

    protected $crudActions = ['view', 'create', 'edit', 'delete'];

    public function mount()
    {
        $this->loadRoles();
        $this->loadPermissions();
    }

    public function render()
    {
        return view('livewire.admin.role-manager')
            ->layout('layouts.app');
    }

    public function loadRoles()
    {
        $this->roles = Role::with('permissions')->orderBy('name')->get();
    }

    public function loadPermissions()
    {
        $permissions = Permission::orderBy('name')->get();

        $grouped = [];
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $menuKey = $parts[0] ?? 'other';
            $action = $parts[1] ?? 'view';

            $grouped[$menuKey][] = $permission;
        }

        $this->groupedPermissions = collect($grouped)->map(function ($perms) {
            return collect($perms)->sortBy(function ($p) {
                return array_search(explode('.', $p->name)[1] ?? '', $this->crudActions);
            })->values();
        });
    }

    public function getMenuLabel($key): string
    {
        return $this->menuLabels[$key] ?? ucfirst(str_replace('-', ' ', $key));
    }

    public function pilihPerGroup($group)
    {
        $permissionsInGroup = $this->groupedPermissions[$group] ?? collect();
        $permissionNames = $permissionsInGroup->pluck('name')->toArray();

        $allSelected = collect($permissionNames)->every(fn ($p) => in_array($p, $this->selectedPermissions));

        if ($allSelected) {
            $this->selectedPermissions = array_diff($this->selectedPermissions, $permissionNames);
        } else {
            $this->selectedPermissions = array_merge($this->selectedPermissions, $permissionNames);
        }
    }

    public function isAllSelectedInGroup($group): bool
    {
        $permissionsInGroup = $this->groupedPermissions[$group] ?? collect();
        $permissionNames = $permissionsInGroup->pluck('name')->toArray();

        return collect($permissionNames)->every(fn ($p) => in_array($p, $this->selectedPermissions));
    }

    public function store()
    {
        $this->validate([
            'roleName' => 'required|string|max:255|unique:roles,name',
            'selectedPermissions' => 'required|array|min:1',
        ]);

        if ($this->isEditMode && $this->editingRoleId) {
            $role = Role::find($this->editingRoleId);
            $role->update(['name' => $this->roleName]);
            $role->syncPermissions($this->selectedPermissions);
            session()->flash('success', 'Role berhasil diperbarui.');
        } else {
            $role = Role::create(['name' => $this->roleName]);
            $role->givePermissionTo($this->selectedPermissions);
            session()->flash('success', 'Role berhasil dibuat.');
        }

        $this->resetForm();
        $this->loadRoles();
    }

    public function edit($roleId)
    {
        $role = Role::with('permissions')->find($roleId);

        $this->isEditMode = true;
        $this->editingRoleId = $roleId;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }

    public function delete($roleId)
    {
        $role = Role::find($roleId);

        if ($role && $role->name !== 'Super Admin') {
            $role->delete();
            session()->flash('success', 'Role berhasil dihapus.');
            $this->loadRoles();
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->roleName = '';
        $this->selectedPermissions = [];
        $this->isEditMode = false;
        $this->editingRoleId = null;
    }
}
