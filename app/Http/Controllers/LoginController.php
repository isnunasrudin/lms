<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nisn' => 'required',
            'password' => 'required'
        ]);

        $student = Student::whereNisn($request->nisn)->wherePassword($request->password)->firstOrFail();

        Auth::guard('student')->login($student);

        return to_route('home');
        
    }

}
