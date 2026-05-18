<?php

namespace App\Livewire\Keuangans;

use App\Models\Keuangan;
use App\Models\Usulan;
use Livewire\Component;
use Livewire\WithPagination;

class KeuangansIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $expandedRows = [];

    public function toggleRow($usulanId)
    {
        if (isset($this->expandedRows[$usulanId])) {
            unset($this->expandedRows[$usulanId]);
        } else {
            $this->expandedRows[$usulanId] = true;
        }
    }

    public function selesai($usulanId, $pegawaiId)
    {
        Keuangan::where('usulan_id', $usulanId)
            ->where('pegawai_id', $pegawaiId)
            ->update(['selesai_at' => now()]);
    }

    public function batalSelesai($usulanId, $pegawaiId)
    {
        Keuangan::where('usulan_id', $usulanId)
            ->where('pegawai_id', $pegawaiId)
            ->update(['selesai_at' => null]);
    }

    public function render()
    {
$usulans = Usulan::whereHas('usulanPegawais', function ($q) {
                $q->where('status', 'approved');
            })
            ->where('nama_kegiatan', 'like', '%'.$this->search.'%')
            ->with(['usulanPegawais' => function ($q) {
                $q->where('status', 'approved')
                    ->with(['kepegawaian', 'kepegawaian.pangkat', 'keuangans']);
            }])
            ->withCount(['usulanPegawais' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->latest()
            ->paginate(10);

        return view('livewire.keuangans.keuangans-index', [
            'usulans' => $usulans,
        ]);
    }
}