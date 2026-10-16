<x-layout>
    <div
        class="mx-auto flex min-h-[calc(100vh-8rem)] max-w-5xl flex-col py-6 sm:py-8"
        x-data="{
            message: '',
            messages: [],
            loading: false,
            error: '',

        async sendMessage() {
            const text = this.message.trim();

            if (!text || this.loading) {
                return;
            }

            this.messages.push({
                role: 'user',
                content: text
            });

            this.message = '';
            this.error = '';
            this.loading = true;

            this.$nextTick(() => {
                this.$refs.messagesContainer.scrollTop =
                    this.$refs.messagesContainer.scrollHeight;
            });

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
                this.error =
                    error.response?.data?.message ??
                    'Unable to contact the assistant. Please try again.';
            } finally {
                this.loading = false;

                this.$nextTick(() => {
                    this.$refs.messagesContainer.scrollTop =
                        this.$refs.messagesContainer.scrollHeight;
                });
            }
        }
    }"
>
    <!-- Header -->
    <header class="mb-6">
        <div class="flex items-center gap-4">
            <div
                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary text-primary-foreground shadow-sm"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.75 17.25h4.5m-5.25 3h6.75M12 3a7.5 7.5 0 00-4.5 13.5v.75h9v-.75A7.5 7.5 0 0012 3z"
                    />
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">
                    AI Assistant
                </h1>

                <p class="mt-1 text-sm text-muted-foreground">
                    Your AI assistant for the Ideas application.
                </p>
            </div>
        </div>
    </header>

    <!-- Chat container -->
    <div
        class="flex min-h-[620px] flex-1 flex-col overflow-hidden rounded-2xl border bg-background shadow-sm"
    >
        <!-- Chat header -->
        <div class="flex items-center justify-between border-b px-5 py-4">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span

                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"
                    ></span>

                    <span
                        class="relative inline-flex h-3 w-3 rounded-full bg-green-500"
                    ></span>
                </span>

                <div>
                    <p class="text-sm font-semibold">
                        Assistant
                    </p>

                    <p class="text-xs text-muted-foreground">
                        Online
                    </p>
                </div>
            </div>

            <span
                class="rounded-full bg-muted px-3 py-1 text-xs text-muted-foreground"
            >
                Phase 1
            </span>
        </div>

        <!-- Messages -->
        <div
            x-ref="messagesContainer"
            class="flex-1 space-y-5 overflow-y-auto p-5 sm:p-6"
        >
            <!-- Empty state -->
            <template x-if="messages.length === 0">
                <div class="flex min-h-[430px] flex-col items-center justify-center text-center">
                    <div
                        class="mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-muted"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-8 w-8 text-muted-foreground"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8.25 10.5h.008v.008H8.25V10.5zm3.75 0h.008v.008H12V10.5zm3.75 0h.008v.008h-.008V10.5z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 12c0 4.142-4.03 7.5-9 7.5a9.83 9.83 0 01-3.45-.61L4.5 20.25l1.36-3.63A7.06 7.06 0 013 12c0-4.142 4.03-7.5 9-7.5s9 3.358 9 7.5z"
                            />
                        </svg>
                    </div>

                    <h2 class="text-lg font-semibold">
                        How can I help?
                    </h2>

                    <p class="mt-2 max-w-md text-sm leading-6 text-muted-foreground">
                        Ask me anything. In this first version, I can chat with you,
                        but I don't have access to your Ideas or application data yet.
                    </p>
                </div>
            </template>

            <!-- Messages -->
            <template
                x-for="(item, index) in messages"
                :key="index"
            >
                <div
                    class="flex"
                    :class="item.role === 'user' ? 'justify-end' : 'justify-start'"
                >
                    <div
                        class="max-w-[85%] rounded-2xl px-4 py-3 text-sm leading-6 shadow-sm sm:max-w-[75%]"
                        :class="
                            item.role === 'user'
                                ? 'rounded-br-md bg-primary text-primary-foreground'
                                : 'rounded-bl-md bg-muted text-foreground'
                        "
                    >
                        <p
                            class="whitespace-pre-line"
                            x-text="item.content"
                        ></p>
                    </div>
                </div>
            </template>

            <!-- Loading -->
            <div
                x-show="loading"
                x-cloak
                class="flex justify-start"
            >
                <div class="rounded-2xl rounded-bl-md bg-muted px-4 py-3">
                    <div class="flex items-center gap-1.5">
                        <span class="h-2 w-2 animate-bounce rounded-full bg-muted-foreground"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-muted-foreground [animation-delay:150ms]"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-muted-foreground [animation-delay:300ms]"></span>
                    </div>
                </div>
            </div>

            <!-- Error -->
            <div
                x-show="error"
                x-cloak
                class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600"
                x-text="error"
            ></div>
        </div>

        <!-- Input -->
        <div class="border-t bg-muted/30 p-4 sm:p-5">
            <form
                class="relative"
                @submit.prevent="sendMessage()"
            >
                <textarea
                    x-model="message"
                    class="min-h-14 w-full resize-none rounded-xl border bg-background px-4 py-3 pr-16 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
                    placeholder="Message the AI Assistant..."
                    maxlength="4000"
                    rows="2"
                    required
                    @keydown.ctrl.enter.prevent="sendMessage()"
                    @keydown.meta.enter.prevent="sendMessage()"
                ></textarea>

                <button
                    type="submit"
                    class="absolute bottom-2.5 right-2.5 flex h-10 w-10 items-center justify-center rounded-lg bg-primary text-primary-foreground transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-40"
                    :disabled="loading || !message.trim()"
                    aria-label="Send message"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 12L3.27 3.125A59.769 59.769 0 0121 12a59.769 59.769 0 01-17.73 8.875L6 12zm0 0h9.75"
                        />
                    </svg>
                </button>
            </form>

            <div class="mt-2 flex items-center justify-between px-1 text-xs text-muted-foreground">
                <span>Ctrl + Enter to send</span>
                <span x-text="`${message.length}/4000`"></span>
            </div>
        </div>
    </div>
</div>

</x-layout>
