@props(['idea' => new App\Models\Idea()])

<x-modal name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}"
    title="{{ $idea->exists ? 'Edit Idea' : 'Create Idea' }}">

    <form x-data="{
        selectedStatus: @js(old('status', $idea->status->value ?? 'pending')),
        imageSelected: false,
        newLink: '',
        links: @js(old('links', $idea->links ?? [])),
        newStep: '',
        steps: @js(old('steps', $idea->steps->map(fn($step) => [
            'description' => $step->description,
            'completed' => $step->completed,
        ]))),
        addLink() {
            const value = (this.newLink || this.$refs.newLink?.value || '').trim();

            if (!value) {
                return;
            }

            this.links.push(value);
            this.newLink = '';
        },
    }" method="POST"
        action="{{ $idea->exists ? route('ideas.update', $idea) : route('ideas.store') }}"
        :enctype="imageSelected ? 'multipart/form-data' : 'application/x-www-form-urlencoded'">

        @csrf

        @if ($idea->exists)
            @method('PATCH')
        @endif

        <input type="hidden" name="status" :value="selectedStatus">

        {{-- Scrollable form content --}}
        <div class="pr-2">
            <div class="space-y-5">

                {{-- Title --}}
                <x-form.field label="Title" name="title" placeholder="Your Idea Title" :value="old('title', $idea->title ?? '')" required />

                {{-- Status --}}
                <div>
                    <label class="label mb-2 block font-medium">
                        Status
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach (App\IdeaStatus::cases() as $status)
                            <button type="button"
                                :class="selectedStatus === '{{ $status->value }}' ? 'btn' : 'btn btn-outlined'"
                                data-test="button-status-{{ $status->value }}"
                                @click="selectedStatus = '{{ $status->value }}'">
                                {{ $status->label() }}
                            </button>
                        @endforeach
                    </div>
                    @error('status')
                        <p class="mt-2 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description --}}
                <x-form.field label="Description" name="description" placeholder="Your Idea Description"
                    :value="old('description', $idea->description ?? '')" />

                {{-- Featured Image --}}
                <div class="space-y-2">
                    <label for="image" class="label">
                        Featured Image
                    </label>

                    @if ($idea->image)
                        <div class="relative overflow-hidden rounded-lg">
                            <img src="{{ asset('storage/' . $idea->image) }}" alt="{{ $idea->title }}"
                                class="h-40 w-full object-cover">

                            <button type="button" class="btn btn-primary mt-2 h-10 w-full"
                                onclick="if(confirm('Are you sure you want to delete this image?')) document.getElementById('delete-image').submit();">
                                Remove Image
                            </button>
                        </div>
                    @endif

                    <input type="file" name="image" accept="image/*"
                        @change="imageSelected = $event.target.files.length > 0">
                    <x-form.error name="image" />
                </div>

                {{-- Steps --}}
                <div>
                    <fieldset class="space-y-2">
                        <legend class="label">
                            Actionable Steps
                        </legend>

                        <template x-for="(step, index) in steps" :key="index">
                            <div class="flex items-center gap-3">
                                <input
                                    :name="`steps[${index}][description]`"
                                    x-model="step.description"
                                    class="input flex-1"
                                >

                                <input
                                    type="hidden"
                                    :name="`steps[${index}][completed]`"
                                    :value="step.completed ? '1' : '0'"
                                >

                                <button
                                    type="button"
                                    class="cursor-pointer form-muted-icon"
                                    aria-label="Remove step"
                                    @click="steps.splice(index, 1)"
                                >
                                    <x-icons.close />
                                </button>
                            </div>
                        </template>

                        <div class="flex items-center gap-3">
                            <input x-model="newStep" id="new-step" placeholder="What needs to be done?"
                                class="input flex-1" spellcheck="false" data-test="new-step"
                                @keydown.enter.prevent="if (newStep.trim()) { steps.push({description: newStep.trim(), completed: false}); newStep = ''; }">

                            <button type="button" class="btn"
                                @click="if (newStep.trim()) { steps.push({description: newStep.trim(), completed: false}); newStep = ''; }"
                                :disabled="!newStep.trim().length" data-test="button-add-step">
                                <x-icons.plus />
                            </button>
                        </div>
                    </fieldset>
                </div>

                {{-- Links --}}
                <fieldset class="space-y-2">
                    <legend class="label">
                        Links
                    </legend>

                    <template x-for="(link, index) in links" :key="index">
                        <div class="flex items-center gap-3">
                            <input name="links[]" x-model="links[index]" class="input flex-1">

                            <button type="button" class="btn" aria-label="Remove link"
                                @click="links.splice(index, 1)">
                                <x-icons.close />
                            </button>
                        </div>
                    </template>

                    <div class="flex items-center gap-3">
                        <input x-ref="newLink" x-model="newLink" type="url" id="new-link" placeholder="https://example.com"
                            autocomplete="url" class="input flex-1" spellcheck="false" data-test="input-new-link"
                            @keydown.enter.prevent="addLink()">

                        <button type="button" class="btn"
                            @click="addLink()" data-test="button-add-link">
                            <x-icons.plus />
                        </button>
                    </div>
                </fieldset>

            </div>
        </div>

        {{-- Fixed Form Buttons --}}
        <div class="mt-5 flex shrink-0 justify-end gap-3 border-t border-border pt-4">
            <button type="button" @click="$dispatch('close-modal')" class="btn btn-outlined">
                Cancel
            </button>

            <button type="submit" class="btn">
                {{ $idea->exists ? 'Update Idea' : 'Create Idea' }}
            </button>
        </div>

    </form>

    @if ($idea->image)
        <form id="delete-image" method="POST" action="{{ route('ideas.destroy-image', $idea) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif

</x-modal>
