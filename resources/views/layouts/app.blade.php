<!DOCTYPE html>
<html data-theme="cupcake">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    <title>@yield('title', 'Home')</title>
</head>

<body class="min-h-screen flex flex-col">
    <x-layout.navbar />

    {{-- content start --}}
    <div class="flex-grow">
        @yield('content')
    </div>
    {{-- content end --}}

    <x-layout.footer />
</body>

</html>
