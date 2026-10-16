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


        <div
            x-data="{
                view: 'idea',
                analysis: null,
                loading: false,
                error: '',
                async selectAnalysis() {
                    if (this.analysis) {
                        this.view = 'analysis';
                        return;
                    }

                    if (this.loading) {
                        return;
                    }

                    this.loading = true;
                    this.error = '';

                    try {
                        const response = await window.axios.post('{{ route('ideas.analyze', $idea) }}');
                        const analysis = response.data?.analysis;

                        if (
                            !analysis
                            || typeof analysis.summary !== 'string'
                            || !Array.isArray(analysis.strengths)
                            || !Array.isArray(analysis.risks)
                            || !Array.isArray(analysis.next_steps)
                        ) {
                            throw new Error('Invalid analysis response.');
                        }

                        this.analysis = analysis;
                        this.view = 'analysis';
                    } catch (error) {
                        this.error = error.response?.data?.message
                            ?? 'Unable to analyze this idea. Please try again.';
                    } finally {
                        this.loading = false;
                    }
                }
            }"
        >
            <h1 class="text-3xl font-bold mt-6 mb-2">
                {{ $idea->title }}
            </h1>

            <div class="mt-2 flex items-center gap-x-3">

                <x-idea.status-label :status="$idea->status" />

                <div class="text-muted-foreground text-sm">
                    {{ $idea->created_at->diffForHumans() }}
                </div>

            </div>

            <div class="mt-5 flex items-center gap-1 rounded-lg border border-border bg-muted/40 p-1 w-fit">
                <button
                    type="button"
                    @click="view = 'idea'"
                    :class="view === 'idea' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'"
                    class="rounded-md px-3 py-1.5 text-sm font-medium transition"
                >
                    Idea
                </button>

                <button
                    type="button"
                    @click="selectAnalysis()"
                    :class="view === 'analysis' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'"
                    class="rounded-md px-3 py-1.5 text-sm font-medium transition disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="loading"
                >
                    ✨ AI Analysis
                </button>
            </div>

            <p x-show="loading" class="mt-3 text-sm text-muted-foreground">
                Analyzing...
            </p>

            <p x-show="error" x-text="error" class="mt-3 text-sm text-red-400"></p>

            <div x-show="view === 'idea'">
                @if ($idea->description)
                    <x-card class="mt-4">

                        <div class="text-muted-foreground whitespace-pre-line">
                            {{ $idea->description }}
                        </div>

                    </x-card>
                @endif
            </div>

            <div x-show="view === 'analysis' && analysis" x-cloak class="mt-4 space-y-4">
                <div>
                    <h2 class="font-semibold">Summary</h2>
                    <p x-text="analysis?.summary" class="mt-1 text-muted-foreground whitespace-pre-line"></p>
                </div>

                <div>
                    <h2 class="font-semibold">Strengths</h2>
                    <ul class="mt-1 list-disc space-y-1 pl-5 text-muted-foreground">
                        <template x-for="strength in analysis?.strengths" :key="strength">
                            <li x-text="strength"></li>
                        </template>
                    </ul>
                </div>

                <div>
                    <h2 class="font-semibold">Risks</h2>
                    <ul class="mt-1 list-disc space-y-1 pl-5 text-muted-foreground">
                        <template x-for="risk in analysis?.risks" :key="risk">
                            <li x-text="risk"></li>
                        </template>
                    </ul>
                </div>

                <div>
                    <h2 class="font-semibold">Next Steps</h2>
                    <ul class="mt-1 list-disc space-y-1 pl-5 text-muted-foreground">
                        <template x-for="step in analysis?.next_steps" :key="step">
                            <li x-text="step"></li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>



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
