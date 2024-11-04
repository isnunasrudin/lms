<?php

namespace App\Exports;

use App\Models\Exam;
use App\Models\Grade;
use App\Models\Rombel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NilaiExport implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle, WithStyles
{
    public function __construct(public Rombel $rombel, public Exam $exam)
    {
        
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $i = 0;
        return $this->exam->grades()->with('student')->whereHas('student', function ($query) {
            $query->where('rombel_id', $this->rombel->id);
        })->get()->sortBy('student.name')->map(function(Grade $grade) use(&$i) {
            return [++$i, $grade->student->name, $grade->student->rombel->name, $grade->grade, $grade->status];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'ROMBEL',
            'NILAI',
            'STATUS'
        ];
    }

    public function title(): string
    {
        return $this->rombel->name;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }
}
