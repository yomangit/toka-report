<html data-theme="nord" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Log In') }}</title>
    <link rel="apple-touch-icon" href="{{ asset('icons.png') }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-status-bar" content="#d42c39">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" sizes="16x16" href="/pwa/icons/ios/16.png">
    <link rel="apple-touch-icon" sizes="20x20" href="/pwa/icons/ios/20.png">
    <link rel="apple-touch-icon" sizes="29x29" href="/pwa/icons/ios/29.png">
    <link rel="apple-touch-icon" sizes="32x32" href="/pwa/icons/ios/32.png">
    <link rel="apple-touch-icon" sizes="40x40" href="/pwa/icons/ios/40.png">
    <link rel="apple-touch-icon" sizes="50x50" href="/pwa/icons/ios/50.png">
    <link rel="apple-touch-icon" sizes="57x57" href="/pwa/icons/ios/57.png">
    <link rel="apple-touch-icon" sizes="58x58" href="/pwa/icons/ios/58.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/pwa/icons/ios/60.png">
    <link rel="apple-touch-icon" sizes="64x64" href="/pwa/icons/ios/64.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/pwa/icons/ios/72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/pwa/icons/ios/76.png">
    <link rel="apple-touch-icon" sizes="80x80" href="/pwa/icons/ios/80.png">
    <link rel="apple-touch-icon" sizes="87x87" href="/pwa/icons/ios/87.png">
    <link rel="apple-touch-icon" sizes="100x100" href="/pwa/icons/ios/100.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/pwa/icons/ios/114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/pwa/icons/ios/120.png">
    <link rel="apple-touch-icon" sizes="128x128" href="/pwa/icons/ios/128.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/pwa/icons/ios/144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/pwa/icons/ios/152.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/pwa/icons/ios/167.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/pwa/icons/ios/180.png">
    <!--<link rel="apple-touch-icon" sizes="192x192" href="/pwa/icons/ios/192.png">-->
    <link rel="apple-touch-icon" sizes="256x256" href="/pwa/icons/ios/256.png">
    <link rel="apple-touch-icon" sizes="512x512" href="/pwa/icons/ios/512.png">
    <link rel="apple-touch-icon" sizes="512x512" href="/pwa/img/icon512_maskable.png">
    <link rel="apple-touch-icon" sizes="512x512" href="/pwa/img/icon512_rounded.png">
    <link rel="apple-touch-icon" sizes="1024x1024" href="/pwa/icons/ios/1024.png">

    <link href="/pwa/icons/ios/1024.png" sizes="1024x1024" rel="apple-touch-startup-image">
    <link href="/pwa/icons/ios/512.png" sizes="512x512" rel="apple-touch-startup-image">
    <link href="/pwa/icons/ios/256.png" sizes="256x256" rel="apple-touch-startup-image">
    <link href="/pwa/icons/ios/192.png" sizes="192x192" rel="apple-touch-startup-image">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow dark:bg-gray-800">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
@stack('scripts')
</body>

</html>
