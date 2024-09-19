<section class=" py-4 bg-[#1a2332] px-4 hidden lg:block">
    <div class="flex justify-between items-center container mx-auto">
        <div class="flex gap-4 text-white text-sm">
            <p class="flex items-center gap-1">
                <i class="text-[#39c9ba] text-xl ph-fill ph-phone"></i>
                Emergency Line: (+88)01722-600730
            </p>

            <p class="flex items-center gap-1">
                <i class="text-[#39c9ba] text-xl ph-fill ph-map-pin"></i>
                Location: Dhaka,Bangladesh
            </p>
        </div>

        <div class="flex items-center gap-4 text-xl">
            <a href="#" class="text-[#39c9ba] hover:text-[#39c9ba] duration-300">
                <i class="ph-fill ph-facebook-logo"></i>
            </a>
            <a href="#" class="text-[#39c9ba] hover:text-[#39c9ba] duration-300">
                <i class="ph-fill ph-twitter-logo"></i>
            </a>
            <a href="#" class="text-[#39c9ba] hover:text-[#39c9ba] duration-300">
                <i class="ph-fill ph-youtube-logo"></i>
            </a>
        </div>
    </div>
</section>

<section class="relative py-4 px-4 shadow border-b border-black/10">
    <div class="container mx-auto flex justify-between">
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="text-4xl font-extrabold title-font">
                <img class="h-[25px] block" src="{{ asset('frontend/home-page-img/logo-no-background.png') }}"
                    alt="">
            </a>
        </div>

        <button @click="asideToggle=!asideToggle" class="lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                viewBox="0 0 256 256">
                <path
                    d="M224,128a8,8,0,0,1-8,8H40a8,8,0,0,1,0-16H216A8,8,0,0,1,224,128ZM40,72H216a8,8,0,0,0,0-16H40a8,8,0,0,0,0,16ZM216,184H40a8,8,0,0,0,0,16H216a8,8,0,0,0,0-16Z">
                </path>
            </svg>
        </button>

        <div class="gap-3 items-center font-semibold hidden lg:block ">
            <div class="text-black lg:flex">
                <a href="{{ route('home') }}" class="py-2 px-3 hover:text-[#39c9ba] @if(Route::is('home')) text-[#39c9ba] @endif duration-300">
                    Home
                </a>
                <a href="{{ route('about') }}" class="py-2 px-3 hover:text-[#39c9ba] @if(Route::is('about')) text-[#39c9ba] @endif duration-300">
                    About Us
                </a>

                <a href="{{ route('doctors.index') }}" class="py-2 px-3 hover:text-[#39c9ba] @if(Route::is('doctors.index')) text-[#39c9ba] @endif duration-300">
                    Doctors
                </a>
                <a href="{{ route('hospitals') }}"
                    class="py-2 px-3 hover:text-[#39c9ba] @if(Route::is('hospitals')) text-[#39c9ba] @endif duration-300 border-r-2 border-white/40">
                    Hospitals
                </a>

                <a href="{{ route('contact.index') }}"
                    class="py-2 px-3 hover:text-[#39c9ba] @if(Route::is('contact.index')) text-[#39c9ba] @endif duration-300 border-r-2 border-white/40 pr-8">
                    Contact
                </a>

                @if (auth()->check() && auth()->user()->role == 'user')
                    <form action="{{ route('logout') }}" class="inline-block" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 py-2 px-3 text-red-500 mr-2 cursor-pointer">
                            Logout
                        </button>
                    </form>
                    <a href="{{ route('appointments.index') }}"
                        class="flex items-center gap-2 bg-[#39c9ba] text-white border py-2 px-3 rounded-md hover:bg-[#0b162a] hover:text-[#fff] duration-300 cursor-pointer">
                        My Appointments
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="flex items-center gap-2 bg-[#39c9ba] text-white border py-2 px-3 rounded-md hover:bg-[#0b162a] hover:text-[#fff] duration-300 cursor-pointer">
                        Register/Login
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
<div x-show="asideToggle" @click.self="asideToggle=false" style="display: none;"
    class="fixed top-0 bottom-0 w-full h-full bg-black/50 z-[100] duration-300 ">
    <div class="max-w-[250px] w-full h-full bg-white px-6 py-8 relative">
        <a href="{{ route('home') }}"
            class="text-3xl font-extrabold title-font text-center flex items-center justify-center mb-4">
            <img class="h-[25px] block" src="{{ asset('frontend/home-page-img/logo-no-background.png') }}"
                alt="">
        </a>
        <div class="mb-4">
            <div class="text-black flex flex-col">
                <a href="{{ route('home') }}" class="py-2 px-3 hover:text-[#39c9ba] @if(Route::is('home')) text-[#39c9ba] @endif duration-300">
                    Home
                </a>
                <a href="{{ route('about') }}" class="py-2 px-3 hover:text-[#39c9ba] @if(Route::is('about')) text-[#39c9ba] @endif duration-300">
                    About Us
                </a>

                <a href="{{ route('doctors.index') }}" class="py-2 px-3 hover:text-[#39c9ba] @if(Route::is('doctors.index')) text-[#39c9ba] @endif duration-300">
                    Doctors
                </a>
                <a href="{{ route('hospitals') }}"
                    class="py-2 px-3 hover:text-[#39c9ba] @if(Route::is('hospitals')) text-[#39c9ba] @endif duration-300 border-r-2 border-white/40">
                    Hospitals
                </a>

                <a href="{{ route('contact.index') }}"
                    class="py-2 px-3 hover:text-[#39c9ba] @if(Route::is('contact.index')) text-[#39c9ba] @endif duration-300 border-r-2 border-white/40 pr-8">
                    Contact
                </a>

                <br>

                @if (auth()->check() && auth()->user()->role == 'user')
                    <form action="{{ route('logout') }}" class="inline-block" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 py-2 px-3 text-red-500 mr-2 cursor-pointer">
                            Logout
                        </button>
                    </form>
                    <a href="{{ route('appointments.index') }}"
                        class="flex items-center gap-2 bg-[#39c9ba] text-white border py-2 px-3 rounded-md hover:bg-[#0b162a] hover:text-[#fff] duration-300 cursor-pointer">
                        My Appointments
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="flex items-center gap-2 bg-[#39c9ba] text-white border py-2 px-3 rounded-md hover:bg-[#0b162a] hover:text-[#fff] duration-300 cursor-pointer">
                        Register/Login
                    </a>
                @endif
            </div>
        </div>
        <button @click="asideToggle=false" class="bg-red-500 py-0 px-2 absolute top-0 right-0">
            <i class="text-white ph ph-x"></i>
        </button>
    </div>
</div>
