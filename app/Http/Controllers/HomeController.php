<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
            'exams' => $exam->map(function ($exam) use($grades) {
                $exam->percobaan = $grades[$exam->id] ?? 0;
                $exam->enable = (
                    $exam->percobaan < $exam->attempt &&
                    Carbon::now()->between(Carbon::parse("$exam->date $exam->from"), Carbon::parse("$exam->date $exam->until")));
                return $exam;
            })->toArray()
        ]);
    }
}
