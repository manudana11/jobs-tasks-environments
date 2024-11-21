@props(['job', 'isOnThisPage' => false])

<x-panel class="flex gap-x-6">
    <div>
        <x-employer-logo :employer="$job->employer" />
    </div>
    <div class="flex-1 flex flex-col">
        <a href="" class="self-start text-sm text-gray-500">{{ $job->employer->name }}</a>
            <h3 class="font-bold text-xl mt-3 group-hover:text-blue-800 transition-colors duration-300">
                <a href="{{ $isOnThisPage ? $job->url : route('jobs.show', $job->id) }}" target="_blank">
                   {{ $job->title }}
                </a>
            </h3>
            <p class="text-sm text-gray-500 mt-auto">{{ $job->salary }}</p>
    </div>

    <div>
        @foreach($job->tags as $tag)
            <x-tag :$tag >Back-end</x-tag>
        @endforeach
    </div>
    @if (Auth::id() === $job->employer->user_id)
        <a href="{{ route('jobs.edit', $job) }}" class="mt-4">Edit Job</a>
    @endif
</x-panel>