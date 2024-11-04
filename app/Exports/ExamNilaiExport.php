<?php

namespace App\Exports;

use App\Models\Exam;
use App\Models\Grade;
use App\Models\Rombel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExamNilaiExport implements WithMultipleSheets
{
    public $rombel;
    public function __construct(public Exam $exam)
    {
        $this->rombel = $exam->students()->distinct('rombel_id')->pluck('rombel_id');
        $this->rombel = Rombel::whereIn('id', $this->rombel)->get();
    }
    

    public function sheets(): array
    {
        $result = $this->rombel->mapWithKeys(function(Rombel $rombel, int $key){
            return [$rombel->name => new NilaiExport($rombel, $this->exam)];
        });

        return $result->toArray();
    }
}
