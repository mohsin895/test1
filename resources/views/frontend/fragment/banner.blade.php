@php
    $data = [
        [
            'subtitle' => '01722-600730',
            'title' => 'HELP LINE',
            'imgLink' => 'frontend/home-page-img/chember-1.webp',
            'link' => 'tel://01722-600730',
            'linkText' => 'CALL NOW',
            'bgClass' => 'bg-[#fd5847]',
            'textClass' => 'text-[#fd5847]'
        ],
        [
            'subtitle' => 'Appointment With',
            'title' => 'Doctor',
            'imgLink' => 'frontend/home-page-img/chember-2.webp',
            'link' => url('/doctors'),
            'linkText' => 'VISIT NOW',
            'bgClass' => 'bg-[#39c9ba]',
            'textClass' => 'text-[#39c9ba]',
        ]
    ]
@endphp


<section class="relative flex items-center bg-[#ebf7f6] py-20 px-4">
    <div class="container mx-auto grid lg:grid-cols-2 items-center gap-6">
        <div class="max-w-[550px]">
            <h1 class="font-bold text-center md:text-left text-2xl sm:text-3xl md:text-4xl lg:text-5xl pb-4 title-font text-[#39c9ba]">
                WelCome To SomoyMoto
            </h1>
            <p class="pb-8 font-medium text-black/80 text-center md:text-left text:sm md:text-md lg:text-lg leading-7">
                SomoyMoto is a healthcare platform with a user-friendly platform for booking doctor 
                appointments and efficient schedule tracking. 
                Enhance patient experience and optimize workflow with this innovative, secure solution.
            </p>
            <div class="flex md:justify-start  justify-center">
                <a
                    href="/register"
                    class="font-medium hover:bg-[#0b162a] bg-[#39c9ba] text-white py-2 px-8 rounded shadow border border-black/10 hover:shadow-lg text-lg duration-300">
                    Get Started
                </a>
            </div>
        </div>
        <div class="container mx-auto hidden md:flex flex-wrap justify-center gap-4">
            @foreach ($data as $item)
                <div class="py-6 shadow border border-black/10 bg-white rounded-lg w-[250px] relative overflow-hidden">
                    <img 
                        src="{{ $item['imgLink'] }}" 
                        alt="" 
                        class="h-[80px] aspect-squar m-auto mb-2 z-30">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold pb-1">
                            {{ $item['subtitle'] }}
                        </h3>
                        <h4 class="text-xl font-extrabold mb-6 {{ $item['textClass'] }}">
                            {{ $item['title'] }}
                        </h4>
                        <a  
                            href="{{ $item['link'] }}"
                            class="{{$item['bgClass']}} text-white font-semibold text-sm m-auto px-4 py-2 rounded flex items-center justify-center w-[150px] gap-1 hover:bg-black duration-300">
                            {{ $item['linkText'] }}
                        </a>
                    </div>
                </div>
            @endforeach   
        </div>
        {{-- atafata kroe responsive --}}
        <div class="container mx-auto hidden max-md:block">
            <div class="grid grid-cols-2 gap-4">
                @foreach ($data as $item)
                    <div class="py-6 shadow border border-black/10 bg-white rounded-lg relative overflow-hidden">
                        <img 
                            src="{{ $item['imgLink'] }}" 
                            alt="" 
                            class="h-[80px] max-sm:h-[50px] aspect-squar m-auto mb-2 z-30">
                        <div class="text-center">
                            <h3 class="text-lg font-semibold max-sm:text-sm pb-1">
                                {{ $item['subtitle'] }}
                            </h3>
                            <h4 class="text-xl max-sm:text-[16px] font-extrabold mb-6 {{ $item['textClass'] }}">
                                {{ $item['title'] }}
                            </h4>
                            <a  
                                href="{{ $item['link'] }}"
                                class="{{$item['bgClass']}} text-white font-semibold text-sm m-auto px-2 py-1 rounded flex items-center justify-center max-w-[150px] max-sm:w-[100px] gap-1 hover:bg-black duration-300">
                                {{ $item['linkText'] }}
                            </a>
                        </div>
                    </div>
                @endforeach   
            </div>
        </div>
    </div>
</section>
