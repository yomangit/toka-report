<?php

namespace App\Imports;

use App\Models\Manhours;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ManhoursImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        // Bersihkan data angka: ganti koma dengan titik
        $manhours = isset($row['manhours'])
            ? str_replace(',', '.', $row['manhours'])
            : 0;

        $manpower = isset($row['manpower'])
            ? str_replace(',', '.', $row['manpower'])
            : 0;

        // Pastikan angka valid (convert ke float)
        $manhours = is_numeric($manhours) ? (float) $manhours : 0;
        $manpower = is_numeric($manpower) ? (float) $manpower : 0;

        return new Manhours([
            'date'             => $row['date'],
            'company_category' => $row['company_category'],
            'company'          => $row['company'],
            'department'       => $row['department'],
            'dept_group'       => $row['dept_group'],
            'job_class'        => $row['job_class'],
            'manhours'         => $manhours,
            'manpower'         => $manpower,
        ]);
    }

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

    public function rules(): array
    {
        return [
            'date'      => 'required|date_format:Y/m/d',
            'manhours'  => 'nullable|numeric',
            'manpower'  => 'nullable|numeric',
        ];
    }
}
