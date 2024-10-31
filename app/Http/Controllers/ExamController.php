<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExamController extends Controller
{

    public function setActive(Exam $exam)
    {
        $grade = Auth::guard('student')->user()->grades()->where('status', 'PROGRESS')->firstOrCreate([
            'exam_id' => $exam->id,
        ]);

        return Inertia::render('Exam', [
            'exam' => $exam->load('questions'),
            'grade' => $grade,
            'currentJawabans' => $grade->questions->pluck('pivot.answer', 'id')
        ]);
    }

    public function saveJawaban(Request $request, Exam $exam)
    {
        $grade = Auth::guard('student')->user()->grades()->where('status', 'PROGRESS')->where('exam_id', $exam->id)->firstOrFail();

        $grade->questions()->syncWithoutDetaching([
            $request->question_id => ['answer' => $request->answer]
        ]);

        $this->hitung($grade);

        return response()->noContent();
    }

    private function hitung(Grade $grade)
    {
        $benar = $grade->questions->filter(function($data){
            return $data->options[$data->pivot->answer]['is_correct'];
        })->count();

        $nilai = ($benar / $grade->exam->questions()->count()) * 100;

        $grade->update([
            'grade' => $nilai
        ]);
    }

    public function finish(Request $request, Exam $exam)
    {
        try {
            $grade = Auth::guard('student')->user()->grades()->where('status', 'PROGRESS')->where('exam_id', $exam->id)->firstOrFail();

            $this->hitung($grade);

            $grade->update([
                'status' => 'FINISH',
                'finished_at' => Carbon::now()
            ]);
        } catch (\Throwable $th) {
            
        }

        return redirect()->route('home');
    }
}
