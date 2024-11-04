<?php

namespace App\Http\Controllers;

use App\Exports\ExamNilaiExport;
use App\Models\Exam;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RekapController extends Controller
{
    public function __invoke(Exam $exam, Request $request)
    {
        return Excel::download(new ExamNilaiExport($exam), "REKAP NILAI $exam->name.xlsx");
    }
}
