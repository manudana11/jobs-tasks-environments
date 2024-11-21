<?php

namespace App\Http\Controllers;

use App\Models\Environment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    
        $environments = Auth::user()->environments()->with('tasks')->get();
    
        return view('tasks.index', compact('environments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'environment_id' => 'required|exists:environments,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id', 
        ]);
    
        $task = Task::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'pending',
            'environment_id' => $validated['environment_id'],
        ]);
    
        foreach ($validated['user_ids'] as $userId) {
            $task->users()->attach($userId, ['role' => 'executor']);
        }

        $environment = Environment::findOrFail($validated['environment_id']);
    
        return redirect()->route('environment.show', ['environment' => $environment->id])->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $environment = $task->environment;
        abort_unless(
            $environment->users->contains(Auth::id()) || $task->users->contains(Auth::id()),
            403
        );

        return view('tasks.show', compact('task'));
    }

    public function addUsersToTask(Request $request, Task $task)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        foreach ($validated['user_ids'] as $userId) {
            $task->users()->attach($userId, ['role' => 'executor']);
        }

        return redirect()->route('tasks.show', $task)->with('success', 'Users added to task successfully.');
    }

    public function create()
    {
        $users = User::all();
        $environments = Auth::user()->environments;
        if (request()->has('environment_id')) {
            $environment = $environments->find(request()->get('environment_id'));
            if ($environment) {
                $users = $environment->users;
            }
        }
        return view('tasks.create', compact('users', 'environments'));
    }

    public function getUsersForEnvironment($environmentId)
    {
        $environment = Environment::findOrFail($environmentId);
        $users = DB::table('users')
        ->join('environment_users', 'users.id', '=', 'environment_users.user_id')
        ->where('environment_users.environment_id', $environmentId)
        ->select('users.name', 'users.id')
        ->get();

        return response()->json($users);
    }

    public function createInEnvironment(Environment $environment)
    {
        $tasks = $environment->tasks;
        $users = $environment->users;

        return redirect()->route('environment.show', ['environment' => $environment->id]);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->status = $request->input('status');
        $task->save();

        return redirect()->back()->with('success', 'Task status updated!');
    }

    public function destroy(Task $task)
    {
        $environment = $task->environment;
        $task->delete();
        
        return redirect()->route('environment.show', ['environment' => $environment->id])->with('success', 'Task deleted successfully.');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $users = $task->environment->users; 
        $environment = $task->environment;

        return view('tasks.edit', compact('task', 'users', 'environment'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task); 

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id', 
        ]);

        $task->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        $task->users()->sync($validated['user_ids']);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }
}
