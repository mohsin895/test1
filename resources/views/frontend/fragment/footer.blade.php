<footer class="bg-[#222222] bg bg-no-repeat bg-cover bg-center bg-blend-multiplt">
    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-8 container mx-auto py-20 px-6 text-sm">
        <div class="">
            <h3 class="title-font pb-4 text-4xl font-extrabold text-white">
                <img class="h-[25px] block" src="{{ asset('frontend/home-page-img/logo-no-background.png') }}" alt="">
            </h3>

            <p class="text-[#fff8] mb-4">
                We make it easy to plan your travel, automate your expenses and provide valuable insights.
            </p>
            <div class="flex gap-2">
                <a href="#" title="Facebook" class="w-7 grid content-center justify-center text-white/50">
                    <svg class="w-5 h-5" width="800" height="800" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="M20.9 2H3.1A1.1 1.1 0 0 0 2 3.1v17.8A1.1 1.1 0 0 0 3.1 22h9.58v-7.75h-2.6v-3h2.6V9a3.64 3.64 0 0 1 3.88-4 20.26 20.26 0 0 1 2.33.12v2.7H17.3c-1.26 0-1.5.6-1.5 1.47v1.93h3l-.39 3H15.8V22h5.1a1.1 1.1 0 0 0 1.1-1.1V3.1A1.1 1.1 0 0 0 20.9 2Z" fill="currentColor"/></svg>
                </a>
                <a href="#" title="Twitter" class="w-7 grid content-center justify-center text-white/50">
                    <svg class="w-5 h-5" width="800" height="800" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.91 20.889c8.302 0 12.845-6.885 12.845-12.845 0-.193 0-.387-.009-.58A9.197 9.197 0 0 0 23 5.121a9.15 9.15 0 0 1-2.597.713 4.542 4.542 0 0 0 1.99-2.5 8.98 8.98 0 0 1-2.87 1.091A4.506 4.506 0 0 0 16.23 3a4.52 4.52 0 0 0-4.516 4.516c0 .352.044.696.114 1.03a12.822 12.822 0 0 1-9.305-4.718 4.526 4.526 0 0 0 1.4 6.03 4.566 4.566 0 0 1-2.043-.563v.061a4.524 4.524 0 0 0 3.62 4.428 4.399 4.399 0 0 1-1.189.159c-.29 0-.572-.027-.845-.08a4.514 4.514 0 0 0 4.217 3.135 9.054 9.054 0 0 1-5.608 1.936A8.69 8.69 0 0 1 1 18.873a12.841 12.841 0 0 0 6.91 2.016Z" fill="currentColor"/></svg>
                </a>
                <a href="#" title="Instagram" class="w-7 grid content-center justify-center text-white/50 ">
                    <svg class="w-5 h-5" width="800" height="800" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.5 5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z" fill="#000"/><path fill-rule="evenodd" clip-rule="evenodd" d="M4.5 0A4.5 4.5 0 0 0 0 4.5v6A4.5 4.5 0 0 0 4.5 15h6a4.5 4.5 0 0 0 4.5-4.5v-6A4.5 4.5 0 0 0 10.5 0h-6ZM4 7.5a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0ZM11 4h1V3h-1v1Z" fill="currentColor"/></svg>
                </a>
                <a href="#" title="LinkedIn" class="w-7 grid content-center justify-center text-white/50">
                    <svg class="w-5 h-5" width="800" height="800" viewBox="-2 -2 24 24" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin" class="jam jam-linkedin"><path d="M19.959 11.719v7.379h-4.278v-6.885c0-1.73-.619-2.91-2.167-2.91-1.182 0-1.886.796-2.195 1.565-.113.275-.142.658-.142 1.043v7.187h-4.28s.058-11.66 0-12.869h4.28v1.824l-.028.042h.028v-.042c.568-.875 1.583-2.126 3.856-2.126 2.815 0 4.926 1.84 4.926 5.792zM2.421.026C.958.026 0 .986 0 2.249c0 1.235.93 2.224 2.365 2.224h.028c1.493 0 2.42-.989 2.42-2.224C4.787.986 3.887.026 2.422.026zM.254 19.098h4.278V6.229H.254v12.869z" fill="currentColor"/></svg>
                </a>
            </div>
        </div>

        {{-- privacy-policy --}}
        <div class="flex flex-col text-white/70">
            <h6 class="text-white/70 text-[18px] font-semibold mb-3 border-b pb-3 border-white/40">Solutions</h6>
            <a href="{{ url('/about') }}" class="duration-300 hover:text-[#39c9ba]">About Us</a>
            <a href="{{ route('how_it_works') }}" class="duration-300 hover:text-[#39c9ba]">How It Works</a>
            <a href="#" class="duration-300 hover:text-[#39c9ba]">Our Services</a>
            <a href="{{ route('contact.index') }}" class="duration-300 hover:text-[#39c9ba]">Contact Us</a>
            <a href="{{ route('join_as_doctor') }}" class="duration-300 hover:text-[#39c9ba]">Join as a Doctor</a>
        </div>

        <div class="flex flex-col text-white/70">
            <h6 class="text-white/70 text-[18px] font-semibold mb-3 border-b pb-3 border-white/40">Help & Policies</h6>
            <a href="{{ route('specialization') }}" class="duration-300 hover:text-[#39c9ba]">Specializations</a>
            <a href="{{ route('hospitals') }}" class="duration-300 hover:text-[#39c9ba]">Hospitals</a>
            <a href="{{ route('doctors.index') }}" class="duration-300 hover:text-[#39c9ba]">Doctors</a>
            <a href="{{ route('terms_conditions') }}" class="duration-300 hover:text-[#39c9ba]">Terms & Conditions</a>
            <a href="{{ route('privacy_policy') }}" class="duration-300 hover:text-[#39c9ba]">Privacy Policy</a>
        </div>
        <div class="flex flex-col text-white/70">
            <h6 class="text-white/70 text-[18px] font-semibold mb-3 border-b pb-3 border-white/40">Company</h6>
            <div class="mb-6 font-light space-y-2">
                <a href="tel:01722-600730" class="flex items-center gap-2 hover:text-[#39c9ba]">
                    <i class="ph-bold ph-phone text-lg text-white/60"></i>
                     (+88)01722-600730
                </a>
                <a href="mailto:info@somoymoto.com" class="flex items-center gap-2 hover:text-[#39c9ba]" href="#">
                    <i class="ph-bold ph-envelope-simple text-lg text-white/60"></i>
                    info@somoymoto.com
                </a>
                <a class="flex items-center gap-2 hover:text-[#39c9ba]" href="#">
                    <i class="ph-bold ph-map-pin text-lg text-white/60"></i>
                    Dhaka,Bangladesh
                </a>
            </div>
        </div>
    </div>
</footer>

<footer class="bg-[#333] py-2">
    <div class="container mx-auto px-4 flex justify-between items-center text-white/50 max-sm:text-sm">
        <div>&copy; All right reserved</div>
        <div class="">
            Developed by
            <a href="https://skytimesoft.com/" target="_blank" class="text-[#39c9ba] max-sm:text-sm">SkyTime Software Solutions</a>
        </div>
    </div>
</footer>


<style>
    .bg{
        background-image: url({{ asset('frontend/home-page-img/footerBg.webp') }})
    }
</style>