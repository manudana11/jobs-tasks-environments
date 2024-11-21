<x-layout>
    <div class="space-y-10">
        <section class="text-center pt-6">
            <h1 class="font-bold text-4xl">Let's Find Your Next Job</h1>
            {{-- <form action="" class="mt-6">
                <input type="text" placeholder="Web Developer..." class="rounded-xl bg-white/10 border-white/10 px-5 py-4 w-full max-w-xl">
            </form> --}}
            <x-forms.form action="/search" class="mt-6">
                <x-forms.input :label="false" name="q" aria-placeholder="Full Stack Developer..." />
            </x-forms.form>
        </section>

        <section class="pt-10">
            <x-section-headings>Featured Jobs</x-section-headings>
            <div class="grid lg:grid-cols-3 gap-8">
                @foreach ($featuredJobs as $job)
                    <x-job-card :$job />
                @endforeach
            </div>
        </section>

        <section>
            <x-section-headings>Tags</x-section-headings>
            <div class="mt-6 space-x-1">
                @foreach($tags as $tag)
                    <x-tag :tag="$tag" />
                @endforeach
            </div>
        </section>

        <section>
            <x-section-headings>Find Jobs</x-section-headings>
            <div class="mt-6 space-y-6">
                @foreach ($jobs as $job)
                    <x-job-card-wide :$job />
                @endforeach
            </div>
        </section>

    </div>
</x-layout>