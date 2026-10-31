@php
    $title = trim(($title ?? config('adminlte.title', 'AdminLTE 4')));
    $authType = $authType ?? 'login'; // login | register
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    {{-- Bootstrap Icons ship via the Vite bundle (imported in resources/css/adminlte.css) --}}
    @vite(['resources/css/adminlte.css', 'resources/js/adminlte.js'])
    @stack('css')
</head>
<body class="{{ $authType }}-page bg-body-secondary">
    <div class="{{ $authType }}-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <img src="{{ asset(config('adminlte.auth_logo.img.path')) }}" alt="{{ config('adminlte.auth_logo.img.alt') }}"
                     class="{{ config('adminlte.auth_logo.img.class') }}"
                     @if(config('adminlte.auth_logo.img.width', null)) width="{{ config('adminlte.auth_logo.img.width') }}" @endif
                     @if(config('adminlte.auth_logo.img.height', null)) height="{{ config('adminlte.auth_logo.img.height') }}" @endif>
            </div>
            <div class="card-body">
                @yield('auth_body')
            </div>
        </div>
    </div>
    @stack('js')
</body>
</html>
