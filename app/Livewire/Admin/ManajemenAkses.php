<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Kepegawaian;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ManajemenAkses extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedPegawai = null;

    public $roles_dipilih = [];

    public $all_roles = [];

    public function mount()
    {
        $this->all_roles = Role::all();
    }

    public function render()
    {
        $pegawais = Kepegawaian::with('user.roles')
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%'.$this->search.'%');
            })
            ->paginate(10);

        return view('livewire.admin.manajemen-akses', compact('pegawais'))
            ->layout('layouts.app');
    }

    public function aturRole($pegawaiId)
    {
        $this->selectedPegawai = Kepegawaian::with('user.roles')->find($pegawaiId);

        if ($this->selectedPegawai->user) {
            $this->roles_dipilih = $this->selectedPegawai->user->getRoleNames()->toArray();
        } else {
            $this->roles_dipilih = [];
        }
    }

    public function simpanAkses()
    {
        if (! $this->selectedPegawai) {
            return;
        }

        if (! $this->selectedPegawai->user_id) {
            $user = User::create([
                'name' => $this->selectedPegawai->nama,
                'email' => $this->selectedPegawai->email,
                'password' => Hash::make('password123'),
            ]);

            $this->selectedPegawai->user_id = $user->id;
            $this->selectedPegawai->save();
        }

        $user = $this->selectedPegawai->user;

        if ($user) {
            $user->syncRoles($this->roles_dipilih);
        }

        $this->selectedPegawai = null;
        $this->roles_dipilih = [];

        session()->flash('message', 'Akses berhasil diperbarui.');
    }

    public function resetPassword($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->update([
                'password' => Hash::make('password123'),
            ]);
            session()->flash('message', 'Password berhasil direset menjadi "password123"');
        }
    }
}
