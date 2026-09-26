@props(['name', 'title'])

<div
    x-data="{ show: false, name: @js($name) }"
    x-show="show"
    x-trap="show"

    @open-modal.window="show = ($event.detail.name === name)"
    @close-modal.window="show = false"
    @keydown.escape.window="show = false"

    x-transition:enter="ease-out duration-250"
    x-transition:enter-start="opacity-0 -translate-y-4"
    x-transition:enter-end="opacity-100"

    x-transition:leave="ease-in duration-250"
    x-transition:leave-start="opacity-100 -translate-y-4"
    x-transition:leave-end="opacity-0 -translate-y-4"

    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs px-4 py-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-{{ $name }}-title"
    :aria-hidden="!show"
    tabindex="-1"
    id="modal-{{ $name }}"
    x-cloak
>
    <x-card
        is="div"
        @click.outside="show = false"
        class="relative w-[95vw] max-w-7xl max-h-[92dvh] overflow-auto p-6 shadow-xl"
    >
        <div class="flex items-center justify-between mb-4">
            <h2
                id="modal-{{ $name }}-title"
                class="text-2xl font-bold text-foreground"
            >
                {{ $title }}
            </h2>

            <button
                @click="show = false"
                type="button"
                class="text-muted-foreground hover:text-foreground cursor-pointer text-xl"
            >
                &times;
            </button>
        </div>

        <div>
            {{ $slot }}
        </div>
    </x-card>
</div>
