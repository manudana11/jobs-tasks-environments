<x-layout>
    <x-page-heading>Edit Job {{ $job->title }}</x-page-heading>

    <x-forms.form method="POST" action="/jobs/{{ $job->id }}">
        @csrf
        @method('PATCH')

        <x-forms.input label="Title" name="title" value="{{ old('title', $job->title) }}" placeholder="Full Stack Developer" />
        <x-forms.input label="Salary" name="salary" value="{{ old('salary', $job->salary) }}" placeholder="$ 50.000 USD" />
        <x-forms.input label="Location" name="location" value="{{ old('location', $job->location) }}" placeholder="Valencia, Spain" />

        <x-forms.select label="Schedule" name="schedule">
            <option value="Full Time" {{ old('schedule', $job->schedule) == 'Full Time' ? 'selected' : '' }}>Full Time</option>
            <option value="Part Time" {{ old('schedule', $job->schedule) == 'Part Time' ? 'selected' : '' }}>Part Time</option>
        </x-forms.select>

        <x-forms.input label="URL" name="url" value="{{ old('url', $job->url) }}" placeholder="https://www.edf.global/" />
        <label>
            <input type="checkbox" name="featured" {{ old('featured', $job->featured) ? 'checked' : '' }}> Feature (Costs Extra)
        </label>

        <x-forms.divider />

        <x-forms.input label="Tag (comma separated)" name="tags" value="{{ old('tags', implode(',', $job->tags->pluck('name')->toArray())) }}" placeholder="developer, education, video" />

        <x-forms.button>Update Job</x-forms.button>
    </x-forms.form>
</x-layout>
