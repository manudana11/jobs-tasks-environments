<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateJobRequest;
use App\Models\Job;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::latest()->with(['employer', 'tags'])->get()->groupBy('featured');
        return view('jobs.index', [
            'featuredJobs' => $jobs[0],
            'jobs' => $jobs[1],
            'tags' => Tag::all(),
        ]);
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'title' => ['required'],
            'salary' => ['required'],
            'location' => ['required'],
            'schedule' => ['required', Rule::in(['Part Time', 'Full Time'])],
            'url' => ['required', 'active_url'],
            'tags' => ['nullable'],
            'featured' => ['sometimes', 'boolean'],
        ]);        

        $attributes['featured'] = $request->boolean('featured');

        $employer = Auth::user()->employer;
        if (!$employer) {
            return redirect()->back()->with('error', 'Employer not found.');
        }

        $job = $employer->jobs()->create(Arr::except($attributes, 'tags'));

        if ($attributes['tags'] ?? false) {
            foreach (explode(',', $attributes['tags']) as $tag) {
                $job->tag($tag);
            }
        }

        return redirect('/');

    }

    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        if (Auth::id() !== $job->employer->user_id) {
            return redirect('/')->with('error', 'Unauthorized');
        }

        return view('jobs.edit', compact('job'));
    }

    public function update(UpdateJobRequest $request, Job $job)
    {
    
        if (Auth::user()->employer->id !== $job->employer->id) {
            return redirect('/')->with('error', 'Unauthorized');
        }
    
        $attributes = $request->validated();
        $attributes['featured'] = $request->boolean('featured');
    
        $job->update(Arr::except($attributes, 'tags'));
    
        if ($attributes['tags'] ?? false) {
            $tagNames = explode(',', $attributes['tags']);
            $tagIds = [];
    
            foreach ($tagNames as $name) {
                $tag = Tag::firstOrCreate(['name' => trim($name)]);
                $tagIds[] = $tag->id;
            }
    
            $job->tags()->sync($tagIds);
        }
    
        return redirect('/')->with('success', 'Job updated successfully.');
    }
}
