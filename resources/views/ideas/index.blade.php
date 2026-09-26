<x-layout>

    <div>

        <header class="py-8 md:py-12">

            <h1 class="text-3xl md:text-4xl font-bold mb-2">
                Your Ideas
            </h1>

            <p class="text-muted-foreground text-sm mt-2">
                Here are all the ideas you have submitted. You can view, edit, or delete them as needed.
            </p>

            <x-card x-data @click="$dispatch('open-modal', { name: 'create-idea' })" is="button"
                data-test="create-idea-button" class="mt-10 cursor-pointer h-32 w-full text-left">
                <p>What's the idea?</p>
            </x-card>

        </header>

        {{-- Status Filters --}}

        <div class="flex flex-wrap gap-2">

            <a href="/ideas" class="btn {{ request()->filled('status') ? 'btn-outlined' : '' }}">
                All

                <span class="text-xs pl-3">
                    {{ $statusCounts->get('all', 0) }}
                </span>
            </a>

            @foreach (App\IdeaStatus::cases() as $status)
                <a href="/ideas?status={{ $status->value }}"
                    class="btn {{ request('status') === $status->value ? '' : 'btn-outlined' }}">
                    {{ $status->label() }}

                    <span class="text-xs pl-3">
                        {{ $statusCounts->get($status->value, 0) }}
                    </span>
                </a>
            @endforeach

        </div>

        {{-- Ideas --}}

        <div class="mt-10 text-muted-foreground">

            <div class="grid md:grid-cols-2 gap-6">

                @forelse ($ideas as $idea)
                    <x-card href="{{ route('ideas.show', $idea) }}">

                        {{-- Featured Image --}}

                         @if ($idea->image)
                            <div class="mb-5 overflow-hidden rounded-lg"> <img
                                    src="{{ asset('storage/' . $idea->image) }}"
                                     alt="{{ $idea->title }}"
                                    class="w-full aspect-video object-cover transition-transform duration-300 hover:scale-105">
                            </div>
                        @endif

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

                    <x-card class="col-span-full">
                        <p>No Ideas At This Time</p>
                    </x-card>
                @endforelse

            </div>

        </div>

        {{-- Create Idea Modal --}}

       <x-idea.modal />

    </div>

</x-layout>
