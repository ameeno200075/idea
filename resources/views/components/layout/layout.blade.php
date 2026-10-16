<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Idea</title>
   
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-background text-foreground">

    <x-layout.nav />
    <main class="max-w-7xl mx-auto px-6">
        {{ $slot }}
    </main>

    @auth
        <x-assistant.floating-chat />
    @endauth


   @session('success')
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)"
        x-transition.opacity.duration.300ms
        class="bg-primary px-4 py-3 absolute bottom-4 right-4 rounded-lg text-white z-50"
    >
        {{ $value }}
    </div>
@endsession

</body>

</html>
