<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'INVOICE' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-favicon.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap 5 JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body
    class="d-flex flex-column justify-content-center align-items-center text-center min-vh-100 py-5 bg-light">
    {{ $slot }}
</body>

</html>
