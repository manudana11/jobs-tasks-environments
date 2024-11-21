@props(['job', 'isOnThisPage' => false])

<x-panel class="flex flex-col text-center">
    <div class="self-start text-sm">{{ $job->employer->name }}</div>
    <div class="py-8">
        <h3 class="group-hover:text-blue-800 text-xl font-bold transition-colors duration-300">
            <a href="{{ $isOnThisPage ? $job->url : route('jobs.show', $job->id) }}">
                {{ $job->title }}
            </a>

        </h3>
        <p class="text-sm mt-4">{{ $job->salary }}</p>
    </div>
    <div class="flex justify-between items-center mt-auto">
        <div>
            @foreach($job->tags as $tag)
                <x-tag :$tag size="small" />
            @endforeach
        </div>
        <x-employer-logo :employer="$job->employer" :width="42" />
    </div>
    @if (Auth::id() === $job->employer->user_id)
        <a href="{{ route('jobs.edit', $job) }}" class="mt-4">Edit Job</a>
    @endif
</x-panel>