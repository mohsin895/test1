<div class="bg-[#333] px-4 relative">
    <img alt="Image" class="w-full h-full absolute pointer-events-none" src="{{ asset('frontend/home-page-img/footerBg.webp') }}">
    <div class="container mx-auto grid grid-cols-1 gap-8 border-b relative border-white/20 py-10 md:grid-cols-2 lg:grid-cols-4">
        <div class="">
            <h3 class="title-font pb-4 text-4xl font-extrabold text-white">
                <span class="text-[#39c9ba]">Demo</span>
                Logo
            </h3>
            <div>
                <p class="pb-6 text-white/40">
                    Lorem ipsum dolor sit amet.
                </p>
                <div class="flex gap-2">
                    <a class="grid place-content-center border border-white/20 px-2 py-1 text-white duration-300 hover:text-[#39c9ba]"
                        href="#">
                        <i class="ph-bold ph-facebook-logo text-lg"></i>
                    </a>
                    <a class="grid place-content-center border border-white/20 px-2 py-1 text-white duration-300 hover:text-[#39c9ba]"
                        href="#">
                        <i class="ph-fill ph-twitter-logo text-lg"></i>
                    </a>
                    <a class="grid place-content-center border border-white/20 px-2 py-1 text-white duration-300 hover:text-[#39c9ba]"
                        href="#">
                        <i class="ph-fill ph-instagram-logo text-lg"></i>
                    </a>
                    <a class="grid place-content-center border border-white/20 px-2 py-1 text-white duration-300 hover:text-[#39c9ba]"
                        href="#">
                        <i class="ph-fill ph-youtube-logo text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
        <div>
            <h4 class="mb-2 border-b border-white/20 py-2 text-lg font-semibold text-white">
                About
            </h4>
            <div class="grid gap-2 text-base">
                <a class="duration-300 hover:text-[#39c9ba] text-white" href="{{ url('/about') }}">About Us</a>
                <a class="duration-300 hover:text-[#39c9ba] text-white" href="#">Listing</a>
                <a class="duration-300 hover:text-[#39c9ba] text-white" href="#">How It Works</a>
                <a class="duration-300 hover:text-[#39c9ba] text-white" href="#">Our Services</a>
                <a class="duration-300 hover:text-[#39c9ba] text-white" href="{{ route('contact.index') }}">Contact Us</a>
            </div>
        </div>
        <div class="">
            <h4 class="mb-2 border-b border-white/20 py-2 text-lg font-semibold text-white">
                Useful Links
            </h4>
            <div class="grid gap-2 text-white">
                <a class="duration-300 hover:text-[#39c9ba]" href="#">Specialist</a>
                <a class="duration-300 hover:text-[#39c9ba]" href="#">Hospital</a>
                <a class="duration-300 hover:text-[#39c9ba]" href="#">Download App</a>
                <a class="duration-300 hover:text-[#39c9ba]" href="#">Join as a Doctor</a>
                <a class="duration-300 hover:text-[#39c9ba]" href="#">Privacy Policy</a>
            </div>
        </div>
        <div class="">
            <h4 class="mb-2 border-b border-white/20 py-2 text-lg font-semibold text-white">
                Contact Us
            </h4>
            <div class="grid gap-2 text-white/40">
                <a class="flex items-center gap-2 hover:text-[#39c9ba]" href="#">
                    <i class="ph-bold ph-phone text-lg text-white/60"></i>
                     (+88)01603283901
                </a>
                <a class="flex items-center gap-2 hover:text-[#39c9ba]" href="#">
                    <i class="ph-bold ph-envelope-simple text-lg text-white/60"></i>
                    info@email.com
                </a>
                <a class="flex items-center gap-2 hover:text-[#39c9ba]" href="#">
                    <i class="ph-bold ph-map-pin text-lg text-white/60"></i>
                    Mymensingh,Bangladesh
                </a>
            </div>
        </div>
    </div>
    <div class="container mx-auto justify-between py-10 text-white/70 sm:flex">
        <div class="mb-2 flex items-center sm:mb-0">
            <a class="border-r border-white/50 px-2 duration-300 hover:text-[#39c9ba]" href="#">
                Terms of Service
            </a>
            <a class="px-2 duration-300 hover:text-[#39c9ba]" href="#">
                Privacy Policy
            </a>
        </div>
        <div>
            DemoLogo © 2023 All Right Reserved
        </div>
    </div>
</div>
