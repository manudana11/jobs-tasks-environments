<x-layout>
    <x-page-heading>Create New Task</x-page-heading>

    <x-forms.form method="POST" action="{{ route('tasks.store') }}">

        <x-forms.input label="Name" name="name" placeholder="Task Name" />
        <x-forms.input label="Description" name="description" placeholder="Task Description" />
        <x-forms.input label="Start Date" name="start_time" type="date" />
        <x-forms.input label="Deadline" name="end_time" type="date" />

        <x-forms.select label="Assign Users" name="user_ids[]" multiple id="user_ids">
        </x-forms.select>

        <x-forms.select label="Status" name="status">
            <option value="pending">Pending</option>
            <option value="in-progress">In Progress</option>
            <option value="completed">Completed</option>
        </x-forms.select>

        <x-forms.select label="Environment" name="environment_id" id="environment_id">
            <option value="">Select Environment</option>
            @foreach ($environments as $environment)
                <option value="{{ $environment->id }}">{{ $environment->name }}</option>
            @endforeach
        </x-forms.select>

        <x-forms.divider />

        <x-forms.button>Create Task</x-forms.button>

    </x-forms.form>

    <script>
        document.getElementById('environment_id').addEventListener('change', function() {
        const environmentId = this.value;

        if (environmentId) {
            fetch(`/environments/${environmentId}/users`)
                .then(response => response.json())
                .then(users => {
                    const userSelect = document.getElementById('user_ids');
                    userSelect.innerHTML = '';

                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Select User';
                    userSelect.appendChild(defaultOption);

                    users.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id; 
                        option.textContent = user.name;
                        userSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error al cargar los usuarios:', error);
                });
        } else {
            const userSelect = document.getElementById('user_ids');
            userSelect.innerHTML = '';
        }
    });
    </script>
</x-layout>
