<?php

namespace App\Http\Controllers;

use App\Imports\StudentImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{ 
    public function invoke(Request $request)
    {
        Excel::import(new StudentImport, Storage::path('students.xlsx'));
    }
}
