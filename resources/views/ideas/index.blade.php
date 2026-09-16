
<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">
                Your Ideas
            </h1>

            <p class="text-muted-foreground text-sm mt-2">
                Here are all the ideas you have submitted. You can view, edit, or delete them as needed.
            </p>
        </header>

        <div class="mt-10 text-muted-foreground">
           <div class="grid md:grid-cols-2 gap-6">
    @forelse ($ideas as $idea)
        <x-card href="{{ route('ideas.show', $idea) }}">

            <h3 class="text-foreground text-lg">
                {{ $idea->title }}
            </h3>

            <div class="mt-2">
                <x-idea.status-label :status="$idea->status" />
            </div>

            <div class="mt-5 line-clamp-3">
                {{ $idea->description }}
            </div>

            <div class="mt-4">
                {{ $idea->created_at->diffForHumans() }}
            </div>

        </x-card>
    @empty
        <x-card>
            <p>No Ideas At This Time bb</p>
        </x-card>
    @endforelse
</div>
        </div>
    </div>
</x-layout>

