<?php

namespace App\Livewire\Keuangans;

use App\Models\Usulan;
use Livewire\Component;
use Livewire\WithPagination;

// app/Livewire/Keuangans/KeuangansIndex.php

class KeuangansIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $expandedRows = []; // ⭐ Tambahkan - tracking baris yang terbuka

    // Method untuk toggle expand
    public function toggleRow($usulanId)
    {
        if (isset($this->expandedRows[$usulanId])) {
            unset($this->expandedRows[$usulanId]);
        } else {
            $this->expandedRows[$usulanId] = true;
        }
    }

    public function render()
    {
        $usulans = Usulan::whereHas('usulanPegawais', function ($q) {
            $q->where('status', 'approved');
        })
            ->where('nama_kegiatan', 'like', '%'.$this->search.'%')
            ->with(['usulanPegawais' => function ($q) {
                $q->where('status', 'approved')
                    ->with(['kepegawaian', 'kepegawaian.pangkat']);
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
