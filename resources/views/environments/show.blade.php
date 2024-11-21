<x-layout>
    <div class="max-w-4xl mx-auto p-6 bg-gray-800 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-center text-white mb-4">{{ $environment->name }}</h1>
        <p class="text-lg text-center text-gray-300 mb-6">{{ $environment->description }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-gray-700 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold text-white mb-4">Users in this environment</h2>
                <ul class="space-y-2">
                    @foreach($usersInEnvironment as $user)
                        <li class="flex items-center space-x-2 text-gray-300">
                            <span class="font-medium">{{ $user->name }}</span>
                            <span class="text-sm text-gray-400">({{ $user->pivot->role }})</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-gray-700 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold text-white mb-4">Tasks</h2>
                <ul class="space-y-4">
                    @foreach($tasks as $task)
                        <li class="bg-gray-800 p-4 rounded-lg shadow-sm">
                            <h3 class="font-semibold text-white">
                                {{ $task->name }} - <span class="text-gray-400">{{ $task->status }}</span>
                            </h3>
                            <ul class="mt-2 space-y-1">
                                @foreach($task->users as $user)
                                    <li class="text-sm text-gray-300">{{ $user->name }} ({{ $user->pivot->role }})</li>
                                @endforeach
                            </ul>

                            <form method="POST" action="{{ route('tasks.updateStatus', $task->id) }}" class="mt-2" id="status-form-{{ $task->id }}">
                                @csrf
                                @method('PATCH')
                            
                                <select name="status" class="bg-gray-700 text-white p-2 rounded mb-2" onchange="document.getElementById('status-form-{{ $task->id }}').submit();">
                                    <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </form>

                            <!-- Delete Task Button -->
                            <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 text-white p-3 rounded-lg hover:bg-red-700">
                                    Delete Task
                                </button>
                            </form>
                            
                            <form method="GET" action="{{ route('tasks.edit', $task->id) }}" class="mt-2">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700">
                                    Edit Task
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>

                <button id="addTaskButton" class="mt-6 w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700">Add Task</button>

                <div id="addTaskForm" class="mt-6 hidden bg-gray-700 text-white p-6 rounded-lg shadow-lg">
                    <x-forms.form method="POST" action="{{ route('tasks.store') }}">
                        <x-forms.input label="Name" name="name" placeholder="Task Name" />
                        <x-forms.input label="Description" name="description" placeholder="Task Description" />
                        <x-forms.input label="Start Date" name="start_time" type="date" />
                        <x-forms.input label="Deadline" name="end_time" type="date" />
                        <x-forms.select label="Assign Users" name="user_ids[]" multiple>
                            @foreach($usersInEnvironment as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </x-forms.select>
                        <x-forms.select label="Status" name="status">
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </x-forms.select>
                        <input type="hidden" name="environment_id" value="{{ $environment->id }}">
                        <x-forms.divider />
                        <x-forms.button>Create Task</x-forms.button>
                    </x-forms.form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-gray-700 p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold text-white mb-4">Add Users to this Environment</h3>
        <form action="{{ route('environments.addUsers', $environment) }}" method="POST">
            @csrf
        
            <div>
                <label for="user_ids" class="block text-lg text-gray-300 mb-2">Select Users:</label>
                
                <div style="max-height: 10rem; overflow-y: auto; background-color: #2d3748; padding: 1rem; border: 1px solid #4a5568; border-radius: 0.5rem;">
                    @foreach($usersNotInEnvironment as $user)
                        <div class="flex items-center mb-2">
                            <input 
                                type="checkbox" 
                                id="user_{{ $user->id }}" 
                                name="user_ids[]" 
                                value="{{ $user->id }}" 
                                class="mr-2 text-blue-600 focus:ring focus:ring-blue-500 rounded">
                            <label for="user_{{ $user->id }}" class="text-gray-300">{{ $user->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        
            <button type="submit" class="mt-4 w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700">
                Add Users
            </button>
        </form>
    </div>                

    <script>
        document.getElementById('addTaskButton').addEventListener('click', function() {
            let form = document.getElementById('addTaskForm');
            form.classList.toggle('hidden');
        });
    </script>
</x-layout>
