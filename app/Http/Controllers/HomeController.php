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

        $exam = $student->exams()->with('event')->whereHas('event')->get();

        return Inertia::render('Student/Home', [
            'exams' => $exam->map(function ($ujian) use($grades) {
                $ujian->percobaan = $grades[$ujian->id] ?? 0;
                $ujian->enable = (
                    $ujian->percobaan < $ujian->attempt &&
                    Carbon::now()->between(Carbon::parse("$ujian->date $ujian->from"), Carbon::parse("$ujian->date $ujian->until")));
                return $ujian;
            })->filter(function($ujian) {
                return Carbon::now()->between(
                        Carbon::parse($ujian->date . " 00:00:00"),
                        Carbon::parse($ujian->date . " 23:59:59")
                    );
            })->values()->toArray()
        ]);
    }
}
