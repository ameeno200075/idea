
@props([
    'title' => 'Form Title',
    'description' => 'Form Description',
])
    <div class="flex min-h-[calc(100dvh-4rem)] items-center justify-center px-4">

       <div class="w-full max-w-md rounded-xl border border-border bg-card p-8">

            <div class="text-center ">

                <h1 class="text-3xl font-bold tracking-tight">
                    {{ $title }}
                </h1>

                <p class="mt-1 text-muted-foreground">
                    {{ $description }}
                </p>

            </div>

           {{ $slot }}

        </div>

    </div>


