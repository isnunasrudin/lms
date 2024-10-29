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

        return Inertia::render('Home', [
            'student' => $student,
            'exams' => $student->exams
        ]);
    }
}
