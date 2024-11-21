<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function apply(Request $request, $jobId)
    {
        $user = Auth::user();
        $job = Job::findOrFail($jobId);

        if ($job->applications()->where('user_id', $user->id)->exists()) {
            return back()->with('message', 'Ya has aplicado a este trabajo.');
        }

        JobApplication::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        return back()->with('message', 'Has aplicado exitosamente al trabajo.');
    }
}
