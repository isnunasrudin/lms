<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ExamController extends Controller
{

    public function setActive(Exam $exam, Request $request)
    {
        $exam->load('event');

        if($exam->event->required_seb && !preg_match("/(SEB|cbt-exam-browser)/", $request->userAgent()))
            return Inertia::render('Student/SebRequired', [
                'exam' => $exam,
            ]);

        if($exam->event->bans()->whereStudentId(Auth::guard('student')->user()->id)->where('until', '>=', Carbon::now())->exists())
        {
            return redirect()->route('home');
        }

        if(!(
            Carbon::now()->between(Carbon::parse("$exam->date $exam->from"), Carbon::parse("$exam->date $exam->until")) &&
            Auth::guard('student')->user()->grades()->where('status', 'FINISH')->where('exam_id', $exam->id)->count() < $exam->attempt
        ))
        {
            return redirect()->route('home');
        }

        if(Session::get('exam_id') == $exam->id || !$exam->event->required_token)
        {

            $grade = Auth::guard('student')->user()->grades()->where('status', 'PROGRESS')->firstOrCreate([
                'exam_id' => $exam->id,
            ]);
    
            if($this->expired($grade))
                return $this->finish($request, $exam);

            return Inertia::render('Student/Exam', [
                'exam' => $exam->load(['questions', 'event']),
                'grade' => $grade,
                'currentJawabans' => $grade->questions->pluck('pivot.answer', 'id'),
            ]);
        }

        return Inertia::render('Student/Token', [
            'exam' => $exam,
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

        return $this->setActive($exam, $request);

    }

    public function saveJawaban(Request $request, Exam $exam)
    {
        $request->validate([
            'data' => 'required|array',
            'data.*.question_id' => ['required', Rule::exists('questions', 'id')->where('exam_id', $exam->id)],
            'data.*.answer' => 'required|numeric',
        ]);

        $grade = Auth::guard('student')->user()->grades()->where('status', 'PROGRESS')->where('exam_id', $exam->id)->firstOrFail();

        if($this->expired($grade))
            return $this->finish($request, $exam);

        
        $grade->questions()->syncWithoutDetaching(collect($request->get('data'))->mapWithKeys(function($data){
            return [
                $data['question_id'] => ['answer' => $data['answer']]
            ];
        })->toArray());

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

            $request->validate([
                'data' => 'array',
                'data.*.question_id' => ['required', Rule::exists('questions', 'id')->where('exam_id', $exam->id)],
                'data.*.answer' => 'required|numeric',
            ]);

            $grade = Auth::guard('student')->user()->grades()->where('status', 'PROGRESS')->where('exam_id', $exam->id)->firstOrFail();
        
            if($request->exists('data'))
                $grade->questions()->syncWithoutDetaching(collect($request->get('data'))->mapWithKeys(function($data){
                    return [
                        $data['question_id'] => ['answer' => $data['answer']]
                    ];
                }));

            $this->hitung($grade);

            $grade->update([
                'status' => 'FINISH',
                'finished_at' => Carbon::now()
            ]);

        } catch (\Throwable $th) {
            
        }

        return $request->expectsJson() ? response()->noContent(403) : redirect()->route('home');
    }

    private function expired(Grade $grade) : bool
    {
        $exam = $grade->exam;
        if( Carbon::now()->lte(Carbon::parse($grade->created_at)->addMinutes($exam->duration)->addSeconds(3)) )
        {
            return false;
        }

        return true;
    }
}
