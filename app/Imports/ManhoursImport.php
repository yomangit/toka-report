<?php

namespace App\Imports;

use App\Models\Manhours;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ManhoursImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    use Importable;

    /**
     * Mapping row Excel ke model
     */
    public function model(array $row)
    {
        return new Manhours([
            'date'             => $row['date'],
            'company_category' => $row['company_category'],
            'company'          => $row['company'],
            'department'       => $row['department'],
            'dept_group'       => $row['dept_group'],
            'job_class'        => $row['job_class'],
            'manhours'         => $row['manhours'] ?? 0,   // default 0 jika kosong
            'manpower'         => $row['manpower'] ?? 0,   // default 0 jika kosong
        ]);
    }

    /**
     * Baris dianggap kosong jika semua field penting kosong
     */
    public function isEmptyWhen(array $row): bool
    {
        return empty($row['date']) &&
               empty($row['company_category']) &&
               empty($row['company']) &&
               empty($row['department']) &&
               empty($row['dept_group']) &&
               empty($row['job_class']) &&
               empty($row['manhours']) &&
               empty($row['manpower']);
    }

    /**
     * Validasi kolom Excel
     */
    public function rules(): array
    {
        return [
            'date'      => 'required|date_format:Y/m/d',
            'manhours'  => 'nullable|numeric',
            'manpower'  => 'nullable|numeric',
        ];
    }
}
