<div
    x-data="{
        open: false,
        message: '',
        messages: [],
        loading: false,
        error: '',
        init() {
            window.addEventListener('open-assistant', () => {
                this.open = true;
            });
        },
        async sendMessage() {
            const text = this.message.trim();

            if (!text || this.loading) {
                return;
            }

            this.messages.push({ role: 'user', content: text });
            this.message = '';
            this.error = '';
            this.loading = true;
            this.scrollToLatest();

            try {
                const response = await window.axios.post(
                    '{{ route('assistant.chat') }}',
                    { message: text }
                );

                this.messages.push({
                    role: 'assistant',
                    content: response.data.message
                });
            } catch (error) {
                this.error = error.response?.data?.message
                    ?? 'Unable to contact the assistant. Please try again.';
            } finally {
                this.loading = false;
                this.scrollToLatest();
            }
        },
        scrollToLatest() {
            this.$nextTick(() => {
                if (this.$refs.messages) {
                    this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight;
                }
            });
        }
    }"
    x-cloak
    class="fixed inset-0 pointer-events-none z-50"
>
    <div
        x-show="open"
        x-transition.opacity
        class="absolute inset-0 bg-black/20 pointer-events-auto sm:hidden"
        @click="open = false"
        aria-hidden="true"
    ></div>

    <section
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="translate-y-3 opacity-0 scale-95"
        x-transition:enter-end="translate-y-0 opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-y-0 opacity-100 scale-100"
        x-transition:leave-end="translate-y-3 opacity-0 scale-95"
        class="pointer-events-auto absolute bottom-24 right-4 flex h-[min(650px,calc(100vh-8rem))] w-[min(420px,calc(100vw-2rem))] flex-col overflow-hidden rounded-2xl border border-border bg-background shadow-2xl sm:right-6"
        role="dialog"
        aria-modal="false"
        aria-label="AI Assistant"
    >
        <header class="flex items-center justify-between border-b border-border bg-card px-4 py-3">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary text-lg text-primary-foreground">
                    ✨
                </span>
                <div>
                    <h2 class="font-semibold">AI Assistant</h2>
                    <p class="text-xs text-muted-foreground">Ask me anything</p>
                </div>
            </div>

            <button
                type="button"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-xl text-muted-foreground transition hover:bg-muted hover:text-foreground"
                @click="open = false"
                aria-label="Close AI Assistant"
            >
                &times;
            </button>
        </header>

        <div x-ref="messages" class="flex-1 space-y-3 overflow-y-auto p-4">
            <div x-show="messages.length === 0" class="flex h-full items-center justify-center text-center">
                <div class="max-w-xs">
                    <p class="text-lg font-semibold">How can I help?</p>
                    <p class="mt-2 text-sm leading-6 text-muted-foreground">
                        Chat with the assistant without leaving your current page.
                    </p>
                </div>
            </div>

            <template x-for="(item, index) in messages" :key="index">
                <div
                    class="flex"
                    :class="item.role === 'user' ? 'justify-end' : 'justify-start'"
                >
                    <div
                        class="max-w-[85%] rounded-2xl px-3 py-2 text-sm leading-6"
                        :class="item.role === 'user'
                            ? 'rounded-br-md bg-primary text-primary-foreground'
                            : 'rounded-bl-md bg-muted text-foreground'"
                    >
                        <p class="whitespace-pre-line" x-text="item.content"></p>
                    </div>
                </div>
            </template>

            <div x-show="loading" class="flex justify-start">
                <div class="rounded-2xl rounded-bl-md bg-muted px-3 py-3">
                    <div class="flex items-center gap-1.5">
                        <span class="h-2 w-2 animate-bounce rounded-full bg-muted-foreground"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-muted-foreground [animation-delay:150ms]"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-muted-foreground [animation-delay:300ms]"></span>
                    </div>
                </div>
            </div>

            <p x-show="error" x-text="error" class="rounded-lg bg-red-500/10 px-3 py-2 text-sm text-red-400"></p>
        </div>

        <form class="border-t border-border bg-card p-3" @submit.prevent="sendMessage()">
            <div class="flex items-end gap-2">
                <textarea
                    x-model="message"
                    class="min-h-12 flex-1 resize-none rounded-xl border border-border bg-background px-3 py-2 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
                    placeholder="Message the assistant..."
                    maxlength="4000"
                    rows="2"
                    required
                    @keydown.ctrl.enter.prevent="sendMessage()"
                    @keydown.meta.enter.prevent="sendMessage()"
                ></textarea>

                <button
                    type="submit"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary text-primary-foreground transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-40"
                    :disabled="loading || !message.trim()"
                    aria-label="Send message"
                >
                    <span aria-hidden="true">↑</span>
                </button>
            </div>
        </form>
    </section>

    <button
        type="button"
        x-show="!open"
        x-transition
        @click="open = true"
        class="pointer-events-auto absolute bottom-6 right-4 flex h-14 w-14 items-center justify-center rounded-full bg-primary text-2xl text-primary-foreground shadow-lg transition hover:scale-105 hover:shadow-xl sm:right-6"
        aria-label="Open AI Assistant"
    >
        🧐
    </button>
</div>
