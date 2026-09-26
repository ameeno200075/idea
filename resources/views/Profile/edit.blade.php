<x-layout>
    <x-form.form title="Edit your account" description="Need to make a tweak?">

        @if (session('status'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.duration.500ms
                class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif
        
        <form action="/profile" method="POST" class="mt-10 space-y-4 mb-4">

            @csrf
            @method('PATCH')


            <x-form.field name="name" label="Name" :value="$user->name" />

            <x-form.field name="email" label="Email" :value="$user->email" />

            <x-form.field name="password" label="New Password" type="password" :required="false" />

            <button type="submit" class="btn mt-2 h-10 w-full">
                Update Account
            </button>

        </form>

    </x-form.form>

</x-layout>
