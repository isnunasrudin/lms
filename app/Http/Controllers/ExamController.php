<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExamController extends Controller
{

    public function setActive(Exam $exam)
    {
        $grade = Auth::guard('student')->user()->grades()->firstOrCreate([
            'exam_id' => $exam->id,
        ]);

        return Inertia::render('Exam', [
            'exam' => $exam->load('questions'),
            'grade' => $grade,
        ]);
    }
}
