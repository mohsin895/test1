@extends('frontend.master')

@section('bg_banner')
<div class="py-20 text-center text-4xl font-bold">
    <h2>About Us</h2>
</div>
@endsection

@section('content')
    <div class="py-20 px-4">
        <div class="container mx-auto grid lg:grid-cols-2 items-center gap-x-4">
            <div>
                <img 
                    src="frontend/home-page-img/about-1.webp" 
                    alt=""
                    class="max-h-[750px] w-full object-cover rounded-md border border-black/10 shadow"
                >
            </div>
            <div class="sm:p-6">
                <div class="md:text-left text-center  mb-10">
                    <p class="text-xl text-[#39c9ba] font-semibold">Our Mission</p>
                    <h3 class="lg:text-5xl md:text-3xl text-2xl title-font my-1 md:my-2 lg:my-4">Get Your Solutions</h3>
                    <p class="text-black/60 font-medium text-sm md:text-base">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua
                    </p>
                </div>
                <div>
                    <div class="sm:flex items-center border border-black/10 sm:border-none gap-x-6 mb-4 p-4">
                        <div class="bg-[#39c9ba] mx-auto px-6 py-1 w-[100px] aspect-square mb-4 sm:mb-0 rounded-lg grid place-content-center">
                            <i class="text-white text-5xl font-medium ph ph-stethoscope"></i>
                        </div>
                        <div class="text-center sm:text-left">
                            <h3 class="text-lg font-bold mb-2">
                                Search A Doctor
                            </h3>
                            <p class="text-black/60">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                            </p>
                        </div>
                    </div>
                    <div class="sm:flex items-center border border-black/10 sm:border-none gap-x-6 mb-4 p-4">
                        <div class="bg-[#39c9ba] mx-auto px-6 py-1 w-[100px] aspect-square mb-4 sm:mb-0 rounded-lg grid place-content-center">
                            <i class="text-white text-5xl font-medium ph ph-notepad"></i>
                        </div>
                        <div class="text-center sm:text-left">
                            <h3 class="text-lg font-bold mb-2">
                                Schedule an Appointment
                            </h3>
                            <p class="text-black/60">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                            </p>
                        </div>
                    </div>
                    <div class="sm:flex items-center border border-black/10 sm:border-none gap-x-6 mb-4 p-4">
                        <div class="bg-[#39c9ba] mx-auto px-6 py-1 w-[100px] aspect-square mb-4 sm:mb-0 rounded-lg grid place-content-center">
                            <i class="text-white text-5xl font-medium ph ph-users-four"></i>
                        </div>
                        <div class="text-center sm:text-left">
                            <h3 class="text-lg font-bold mb-2">
                                Start Consultation
                            </h3>
                            <p class="text-black/60">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                            </p>
                        </div>
                    </div>
                    <div class="sm:flex items-center border border-black/10 sm:border-none gap-x-6 p-4 ">
                        <div class="bg-[#39c9ba] mx-auto px-6 py-1 w-[100px] aspect-square mb-4 sm:mb-0 rounded-lg grid place-content-center">
                            <i class="text-white text-5xl font-medium ph ph-laptop"></i>
                        </div>
                        <div class="text-center sm:text-left">
                            <h3 class="text-lg font-bold mb-2">
                                Get You Solution
                            </h3>
                            <p class="text-black/60">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection