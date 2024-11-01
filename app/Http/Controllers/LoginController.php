<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
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
                'nisn' => 'NISN / Tanggal Lahir tidak valid'
            ]);
        }
        
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        return to_route('/');
    }

}
