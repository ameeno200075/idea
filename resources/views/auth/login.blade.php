<x-layout>
    <x-form.form title="Login to your account" description="Sign in to access your ideas.">


        <form action="/login" method="POST" class="mt-10 space-y-4 mb-4">

            @csrf


            <x-form.field name="email" label="Email" />


            <x-form.field name="password" label="Password" type="password" />

            <button type="submit" class="btn mt-2 h-10 w-full">
                Login
            </button>

        </form>

    </x-form.form>
</x-layout>
