@extends('frontend.master')

@section('bg_banner')
    <div class="py-20 text-center">
        <h3 class="title-font text-5xl">Get In Touch</h3>
    </div>
@endsection

@section('content')
    <div>
        <div class="container mx-auto py-20">
            {{-- <div class="mb-10 text-center">
                
            </div> --}}

            <div class="grid grid-cols-2 gap-8">
                <div>
                    <div class="mb-5 text-left">
                        <p class="mb-4 text-lg font-semibold text-[#39c9ba]">Meet our team</p>
                        <h3 class="title-font text-3xl">Contact Us</h3>
                    </div>
                    @if (session('success'))
                        <div class="py-2 px-4 bg-green-100 mb-5 text-green-600 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('contact.save') }}" method="POST">
                        @csrf
                            <div class="mb-4 w-full gap-4">
                                <div>
                                    <input class="rounded border border-black/10 px-4 py-3 outline-none w-full" name="name"
                                    placeholder="Your name..." type="text" >
                                    @error('name')
                                        <div class="text-red-500 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4 grid grid-cols-2 gap-4">
                                <div class="grid">
                                    <input class="rounded border border-black/10 px-4 py-3 outline-none" name="phone"
                                    placeholder="Phone number..." type="text" >
                                    @error('phone')
                                        <div class="text-red-500 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="grid">
                                    <input class="rounded border border-black/10 px-4 py-3 outline-none" name="email"
                                        placeholder="Your email..." type="email" >
                                    @error('email')
                                        <div class="text-red-500 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-5">
                                <textarea name="message" class="h-[150px] w-full resize-none rounded border border-black/10 px-4 py-3 outline-none"
                                    placeholder="Your Message..." ></textarea>
                                @error('message')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <button
                                class="text-md m-auto rounded bg-[#39c9ba] px-4 py-2 font-semibold text-white shadow duration-300 hover:bg-black"
                                type="submit">
                                Send Message
                            </button>
                    </form>
                </div>
                <div>
                    <iframe allowfullscreen="" class="h-full w-full" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5920.365032025799!2d90.41214264790365!3d24.746743712944443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x37564fbe84f6cbe1%3A0x9fdd8aa3d42ccc3e!2sMymensingh%20District!5e0!3m2!1sen!2sbd!4v1691591566909!5m2!1sen!2sbd">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
@endsection
