<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function __invoke()
    {
        return Inertia::render("Login");
    }

    public function login(Request $request)
    {
        $request->validate([
            'nisn' => 'required',
            'password' => 'required'
        ]);

        try {
            $student = Student::whereNisn($request->nisn)->wherePassword($request->password)->firstOrFail();

            Auth::guard('student')->login($student);

            return to_route('home');
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'nisn' => 'NISN / Kata Sandi tidak valid'
            ]);
        }
        
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        return to_route('/');
    }

}
