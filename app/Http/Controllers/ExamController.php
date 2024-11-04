<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ExamController extends Controller
{

    public function setActive(Exam $exam, Request $request)
    {
        $grade = Auth::guard('student')->user()->grades()->where('status', 'PROGRESS')->firstOrCreate([
            'exam_id' => $exam->id,
        ]);

        if($this->expired($grade))
            return $this->finish($request, $exam);

        if(Session::get('exam_id') == $exam->id || !$exam->event->required_token)
        {
            return Inertia::render('Exam', [
                'exam' => $exam->load('questions'),
                'grade' => $grade,
                'currentJawabans' => $grade->questions->pluck('pivot.answer', 'id'),
                'student' => Auth::guard('student')->user(),
            ]);
        }

        return Inertia::render('Token', [
            'exam' => $exam->load('questions'),
            'grade' => $grade,
            'currentJawabans' => $grade->questions->pluck('pivot.answer', 'id'),
            'student' => Auth::guard('student')->user(),
        ]);
    }

    public function verifyToken(Exam $exam, Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        if($exam->event->token != $request->get('token')) {
            throw ValidationException::withMessages([
                'token' => 'Token Tidak Valid!'
            ]);
        }

        Session::put('exam_id', $exam->id);

        return redirect()->back();

    }

    public function saveJawaban(Request $request, Exam $exam)
    {
        $request->validate([
            'answer' => 'required',
            'email' => 'required|email'
        ]);

        $grade = Auth::guard('student')->user()->grades()->where('status', 'PROGRESS')->where('exam_id', $exam->id)->firstOrFail();

        if($this->expired($grade))
            return $this->finish($request, $exam);

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

    private function expired(Grade $grade) : bool
    {
        $exam = $grade->exam;
        if( Carbon::now()->lte(Carbon::parse($grade->created_at)->addMinutes($exam->duration)) )
        {
            return false;
        }

        return true;
    }
}
