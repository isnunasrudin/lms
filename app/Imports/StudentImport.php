<?php

namespace App\Imports;

use App\Models\Rombel;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class StudentImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'name' => strtoupper(trim($row[2])),
            'nisn' => trim($row[1]),
            'gender' => trim($row[3]),
            'born_date' => trim($row[4]),
            'password' => trim($row[5]),
            'rombel_id' => Rombel::firstOrCreate([
                'name' => trim($row[6]),
                'level' => preg_replace("/[^0-9]/", '', $row[6]),
            ])->id
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
