<x-layout>
    <x-page-heading>{{ $job->title }}</x-page-heading>

    <x-job-card-wide :$job :isOnThisPage="true" />

    @if (Auth::check() && !$job->applications()->where('user_id', Auth::id())->exists())
        <div class="mt-4">
            <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">
                    Aplicar
                </button>
            </form>
        </div>
    @elseif (Auth::check() && $job->applications()->where('user_id', Auth::id())->exists())
        <p class="text-green-500 mt-4">You have already applied this job.</p>
    @endif
</x-layout>
