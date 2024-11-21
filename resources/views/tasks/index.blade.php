<x-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-8 text-white">Your Environments</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($environments as $environment)
                <div class="bg-gray-800 text-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">{{ $environment->name }}</h2>
                    <p class="text-gray-400 mb-4">{{ $environment->description }}</p>

                    @if($environment->tasks->isEmpty())
                        <p class="text-gray-500">No tasks available for this environment.</p>
                    @else
                    <ul class="space-y-2">
                        @foreach($environment->tasks as $task)
                            <li class="p-4 bg-gray-700 rounded-lg shadow-sm">
                                <h3 class="text-gray-200 font-medium">
                                    {{ $task->name }} 
                                    <span class="text-sm text-gray-400">({{ $task->status }})</span>
                                </h3>
                                <p class="text-sm text-gray-300">{{ $task->description }}</p>
                    
                                <form method="POST" action="{{ route('tasks.updateStatus', $task->id) }}" class="mt-2" id="status-form-{{ $task->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="bg-gray-700 text-white p-2 rounded" onchange="document.getElementById('status-form-{{ $task->id }}').submit()">
                                        <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </form>
                    
                                
                                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-all duration-300">
                                        Delete Task
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                    @endif

                    <a href="{{ route('environments.show', $environment) }}" 
                       class="block mt-4 bg-blue-600 text-white text-center py-2 px-4 rounded-lg border-2 border-transparent hover:bg-blue-700 hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all duration-300">
                        View Details
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-layout>
