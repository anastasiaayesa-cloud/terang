<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Perencanaan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PerencanaanImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        if (empty($row['nama_komponen'])) {
            return null;
        }

        return new Perencanaan([
            'kode' => $row['kode'] ?? null,
            'nama_komponen' => $row['nama_komponen'],
            'dokumen_perencanaan_id' => ! empty($row['dokumen_perencanaan_id']) ? (int) $row['dokumen_perencanaan_id'] : null,
            'usulan_id' => ! empty($row['usulan_id']) ? (int) $row['usulan_id'] : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_komponen' => 'required|string',
            'dokumen_perencanaan_id' => 'nullable|integer|exists:dokumen_perencanaans,id',
            'usulan_id' => 'nullable|integer|exists:usulans,id',
        ];
    }
}
