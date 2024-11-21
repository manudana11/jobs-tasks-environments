<x-layout>
    <h1>{{ $task->name }}</h1>
    <p>{{ $task->description }}</p>
    <p>Status: {{ $task->status }}</p>
    <p>Start: {{ $task->start_time }} - End: {{ $task->end_time }}</p>

    <h2>Assigned Users</h2>
    <ul>
        @foreach($task->users as $user)
        <li>{{ $user->name }} ({{ $user->pivot->role }})</li>
        @endforeach
    </ul>

    <form action="{{ route('tasks.addUsers', $task) }}" method="POST">
        @csrf
        <label for="user_ids">Add Users</label>
        <select name="user_ids[]" multiple>
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <button type="submit">Add Users</button>
    </form>
</x-layout>
