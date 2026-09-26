<x-layout>

    <div class="py-8 max-w-4xl mx-auto">

        <div class="flex items-center justify-between">

            <a href="{{ route('ideas.index') }}" class="btn">
                <x-icons.back class="mr-2" />
                Back to all Ideas
            </a>

            <div class="flex items-center gap-x-3">

                <button class="btn btn-outlined" data-test="edit-idea-button" x-data
                    @click="$dispatch('open-modal', { name: 'edit-idea' })" is="button">
                    <x-icons.edit />
                    Edit Idea
                </button>

                <form method="POST" action="{{ route('ideas.destroy', $idea) }}"
                    onsubmit="return confirm('Are you sure you want to delete this idea?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="btn border border-red-500 text-red-500 hover:bg-red-500 hover:text-white flex items-center gap-x-2">
                        <x-icons.delete />
                        Delete Idea
                    </button>
                </form>

            </div>

        </div>

        {{-- -photo --}}

        <div class="mt-8 space-y-6">
            @if ($idea->image)
                <div class="rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $idea->image) }}"alt="" class="w-full h-auto object-cover">

                </div>
            @endif
        </div>


        <h1 class="text-3xl font-bold mt-6 mb-2">
            {{ $idea->title }}
        </h1>

        <div class="mt-2 flex items-center gap-x-3">

            <x-idea.status-label :status="$idea->status" />

            <div class="text-muted-foreground text-sm">
                {{ $idea->created_at->diffForHumans() }}
            </div>

        </div>
        @if ($idea->description)
            <x-card class="mt-4">

                <div class="text-muted-foreground whitespace-pre-line">
                    {{ $idea->description }}
                </div>

            </x-card>
        @endif



        @if ($idea->steps?->count())
            <div>
                <h3 class="font-bold text-xl mt-6 mb-3">Actionable Steps</h3>

                <div class="space-y-2">
                    @foreach ($idea->steps as $step)
                        <x-card>
                            <form method="POST" action="{{ route('steps.update', $step) }}"
                                class="flex items-center gap-x-3">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                    class="size-5 flex items-center justify-center rounded-lg border border-primary {{ $step->completed ? 'bg-primary text-primary-foreground' : '' }}">
                                    @if ($step->completed)
                                        ✓
                                    @endif
                                </button>

                                <span @class(['line-through text-gray-400' => $step->completed])>
                                    {{ $step->description }}
                                </span>
                            </form>
                        </x-card>
                    @endforeach
                </div>
                <x-idea.modal />
            </div>
        @endif



        @if ($idea->links?->count())
            <div>
                <h3 class="font-bold text-xl mt-6 mb-3">Links</h3>
                <div class="space-y-2">

                    @foreach ($idea->links as $link)
                        <x-card :href="is_object($link) ? $link->url : $link" target="_blank"
                            class="text-primary font-medium flex gap-x-3 items-center hover:underline">
                            <x-icons.link class="w-4 h-4" />
                            {{ is_object($link) ? $link->title ?? $link->url : $link }}
                        </x-card>
                    @endforeach
                </div>
            </div>
        @endif
        <x-idea.modal :idea="$idea" />
    </div>

</x-layout>
