<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        $student = Auth::guard('student')->user();
        $grades = $student->grades()->where('status', 'FINISH')->get()->groupBy('exam_id')->map->count();

        $exam = $student->exams;

        return Inertia::render('Home', [
            'student' => $student,
            'exams' => $exam->filter(function ($exam) use($grades) {
                return $exam->attempt > ($grades[$exam->id] ?? 0);
            })
        ]);
    }
}
