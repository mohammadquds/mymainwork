<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alnaierh Gold System</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('GoldSystem.ico') }}">
   <script src="https://cdn.tailwindcss.com"></script>
</head>
@auth
<body class="bg-gray-50 text-gray-900 min-h-screen">

<livewire:nav-bar/>

    <main class="mx-auto max-w-7xl py-8 sm:px-6 lg:px-8">
@endauth
        {{ $slot }}
    </main>
    @fluxScripts

</body>
</html>
