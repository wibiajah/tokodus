<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'TEMPLATE' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navigation')
    @include('frontend.components.navbar')

    <main>
        {{ $slot }}
    </main>

    @include('frontend.components.footer')
</body>

</html>