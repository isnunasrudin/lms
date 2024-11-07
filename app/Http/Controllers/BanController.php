<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BanController extends Controller
{
    public function __invoke(Request $request)
    {
        DB::transaction(function() use ($request) {
            $event = Exam::findOrFail($request->exam_id)->event;

            Cache::lock('ban_' . $event->id . '_' . Auth::guard('student')->user()->id, 5)->get(function () use($event, $request)  {
                $event->bans()->updateOrCreate([
                    'student_id' => Auth::guard('student')->user()->id
                ], [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'until' => Carbon::now()->addMinutes(10),
                ]);
            });

        });

        return response()->noContent();

    }
}
