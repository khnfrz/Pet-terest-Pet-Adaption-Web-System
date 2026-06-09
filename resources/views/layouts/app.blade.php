<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Petty - Pet Adoption')</title>
    <link rel="icon" href="{{ asset('uploads/logo.png') }}" type="image/x-icon">
    @yield('styles')
</head>
<body>
    @yield('content')
    
    @yield('scripts')
</body>
</html>