<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="ie=edge" http-equiv="X-UA-Compatible">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="shortcut icon" href="{{ asset('frontend/home-page-img/logo-no-background.png') }}" type="image/x-icon">
    <link href="phosphoricons.com?weight=regular&size=32&color=000000" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://fontawesome.com/v4/"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .title-font {
            font-family: 'Righteous', cursive;
        }
    </style>

    @stack('css')
</head>

<body x-data="{ asideToggle: false }">
    @include('frontend.fragment.nav')

    @section('banner')
        {{-- @include('frontend.fragment.banner') --}}
    @show
    
    @if(View::hasSection('bg_banner'))
        <div class="bg-no-repeat bg-cover bg-left bg-[url({{ asset('frontend/home-page-img/loginbg.jpeg') }})]">
            <div class="bg-[#000000] bg-opacity-40 text-slate-100 px-4">
                @yield('bg_banner')
            </div>
        </div>
    @endif

    @section('content')
    @show

    @include('frontend.fragment.footer')

    @stack('js')
</body>

</html>
