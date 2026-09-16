<nav class="border-b border-border px-6">
    <div class="max-w-7xl mx-auto h-16 flex  items-center justify-between ">
        <div>
            <a href="/">

                <img src="{{ asset('images/kiro.svg') }}" alt="Idea logo" class="h-8 w-auto">
            </a>
        </div>
        <div class="flex gap-4 items-center">
            @auth
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="btn">Logout</button>
        </form>
            @endauth
            @guest

                <a href="/login">Sign In</a>
                <a href="/register" class="btn">Register</a>
            @endguest

        </div>
    </div>
</nav>
