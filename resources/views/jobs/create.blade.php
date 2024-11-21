<x-layout>
    <x-page-heading>New Job</x-page-heading>

    <x-forms.form method="POST" action="/jobs">

        <x-forms.input label="Title" name="title" placeholder="Full Stack Deveoper" />
        <x-forms.input label="Salary" name="salary" placeholder="$ 50.000 USD" />
        <x-forms.input label="Location" name="location" placeholder="Valencia, Spain" />

        <x-forms.select label="Schedule" name="schedule">
            <option>Full Time</option>
            <option>Part Time</option>
        </x-forms.select>

        <x-forms.input label="URL" name="url" placeholder="https://www.edf.global/" />
        <x-forms.checkbox label="Feature (Costs Extra)" name="featured" />

        <x-forms.divider />

        <x-forms.input label="Tag (comma separated)" name="tags" placeholder="developer, education, video" />

        <x-forms.button>Publish</x-forms.button>

    </x-forms.form>
</x-layout>