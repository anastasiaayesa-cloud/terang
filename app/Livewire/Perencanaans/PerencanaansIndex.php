<?php

declare(strict_types=1);

namespace App\Livewire\Perencanaans;

use App\Imports\PerencanaanImport;
use App\Models\Perencanaan;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class PerencanaansIndex extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';

    public $file_excel;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function importExcel()
    {
        $this->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new PerencanaanImport, $this->file_excel->getRealPath());

            $this->file_excel = null;

            session()->flash('success', 'Data perencanaan berhasil diimport.');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Baris '.$failure->row().': '.implode(', ', $failure->errors());
            }
            session()->flash('error', 'Gagal import: '.implode(' | ', $errors));
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal import: '.$e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $perencanaan = Perencanaan::findOrFail($id);
            $perencanaan->delete();
            session()->flash('success', 'Perencanaan berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus perencanaan: '.$e->getMessage());
        }
    }

    public function render()
    {
        $perencanaans = Perencanaan::query()
            ->with(['dokumenPerencanaan', 'usulan'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('perencanaans.kode', 'like', '%'.$this->search.'%')
                        ->orWhere('perencanaans.nama_komponen', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('livewire.perencanaans.perencanaans-index', compact('perencanaans'))
            ->layout('layouts.app');
    }
}
