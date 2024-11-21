<x-layout>
    <x-page-heading>Edit Task: {{ $task->name }}</x-page-heading>

    <x-forms.form method="POST" action="{{ route('tasks.update', $task) }}">
        @method('PATCH')
        @csrf

        <x-forms.input label="Name" name="name" value="{{ old('name', $task->name) }}" placeholder="Task Name" />
        <x-forms.input label="Description" name="description" value="{{ old('description', $task->description) }}" placeholder="Task Description" />
        <x-forms.input label="Start Date" name="start_time" type="date" value="{{ old('start_time', $task->start_time) }}" />
        <x-forms.input label="Deadline" name="end_time" type="date" value="{{ old('end_time', $task->end_time) }}" />
                

        <x-forms.select label="Assign Users" name="user_ids[]" multiple id="user_ids">
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ in_array($user->id, $task->users->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </x-forms.select>

        <x-forms.select label="Status" name="status">
            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in-progress" {{ $task->status == 'in-progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
        </x-forms.select>

        <x-forms.input label="Environment" name="environment_id" value="{{ $environment->name }}" disabled />

        <x-forms.divider />
        
        <x-forms.button>Update Task</x-forms.button>

    </x-forms.form>
</x-layout>
