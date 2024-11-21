<x-layout>
    <x-page-heading>Create New Environment</x-page-heading>

    <x-forms.form method="POST" action="/environments">
        
        <x-forms.input label="Name" name="name" placeholder="Environment Name" />
        <x-forms.input label="Description" name="description" placeholder="Brief description of the environment" />

        <!-- Aquí puedes agregar un campo para asignar usuarios en el momento de la creación del entorno -->
        <x-forms.select label="Assign Users" name="users[]" multiple>
            <!-- Suponiendo que tengas una lista de usuarios disponibles -->
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </x-forms.select>

        <x-forms.divider />

        <x-forms.button>Create Environment</x-forms.button>

    </x-forms.form>
</x-layout>
