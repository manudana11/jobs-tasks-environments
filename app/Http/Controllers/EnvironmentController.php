<?php

namespace App\Http\Controllers;

use App\Models\Environment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnvironmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $environment = Environment::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        $environment->users()->attach(Auth::id(), ['role' => 'owner']);
        foreach ($validated['users'] as $userId) {
            $environment->users()->attach($userId, ['role' => 'member']);
        }

        return redirect()->route('environments.index')->with('success', 'Environment created successfully.');
    }

    public function show(Environment $environment)
    {
        abort_unless($environment->users->contains(Auth::id()), 403);

        $usersInEnvironment = $environment->users;
        $environmentUsers = $usersInEnvironment->pluck('id');
        $usersNotInEnvironment = User::whereNotIn('id', $environmentUsers)->get();
        $users = User::all();

        $tasks = $environment->tasks()->with('users')->get();
        $environments = Environment::all();

        return view('environments.show', compact('environment', 'tasks', 'users', 'usersInEnvironment', 'usersNotInEnvironment', 'environments'));
    }

    public function addUsersToEnvironment(Request $request, Environment $environment)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        foreach ($validated['user_ids'] as $userId) {
            if (!$environment->users->contains($userId)) {
                $environment->users()->attach($userId, ['role' => 'member']);
            }
        }

        return redirect()->route('environments.show', $environment)->with('success', 'Users added successfully.');
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('environments.create', compact('users'));
    }

    public function index()
    {
        $environments = Environment::all();

        return view('environments.index', compact('environments'));
    }

    public function destroy(Environment $environment)
    {
        $environment->tasks()->delete();

        $environment->delete();

        return redirect()->route('environments.index')->with('success', 'Environment and its tasks deleted successfully.');
    }
}