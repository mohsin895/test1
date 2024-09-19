<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta content="ie=edge" http-equiv="X-UA-Compatible">

        <title inertia>{{ config('app.name', 'DoctorPlusBD') }}</title>
        <link rel="shortcut icon" href="{{ asset('frontend/home-page-img/logo-no-background.png') }}" type="image/x-icon">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css2?family=ABeeZee:ital@0;1&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Advent+Pro:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Advent+Pro:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Alef:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Alef:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Amaranth:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Amaranth:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Gochi+Hand&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Just+Another+Hand&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Alegreya:wght@400;800&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@1,400;1,800&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans+SC:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Allerta+Stencil&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Beau+Rivage&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Allura&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Amaranth:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Amarante&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Amiko:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Amiko:wght@400;700&display=swap'   " rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Andika:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Anonymous+Pro:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Anonymous+Pro:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Antic&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Antic+Didone&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Arapey&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Arapey&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Architects+Daughter&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Arima+Madurai:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Arima+Madurai:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Arvo:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Arvo:wght@400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Asap&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Asset&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@300;400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@300;400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@300;400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Athiti:wght@300;400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Athiti:wght@300;400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Athiti:wght@300;400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Atma:wght@300;400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Atma:wght@300;400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Bad+Script&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Belgrano&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Belleza&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Bentham&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Beau+Rivage&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Bevan&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Bitter:wght@300;400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Bitter:wght@300;400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Bitter:wght@300;400;700&display=swap" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap" rel="stylesheet" >

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead

        <!-- Google tag (gtag.js) --> 
        {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-0FDE8RG36P"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-0FDE8RG36P'); </script> --}}
    </head>
    <body class="antialiased">
        @inertia
    </body>
</html>

