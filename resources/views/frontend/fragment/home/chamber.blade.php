@php
    $data = [
        [
            'subtitle' => 'Appointment By',
            'title' => 'Facebook',
            'buttonText' => 'Book Now',
            'imgLink' => 'frontend/home-page-img/facebook-appointment.png',
            'link' => route('doctors.index'),
            'bgClass' => 'border-2 border-[#3d6cf4] bg-white text-[#3d6cf4] hover:text-white hover:bg-[#3d6cf4]',
            'textClass' => 'text-[#3d6cf4]',
            'border' => 'border-[#3d6cf4]'
        ],
        [
            'subtitle' => 'Appointment By',
            'title' => 'WhatsApp',
            'buttonText' => 'Book Now',
            'imgLink' => 'frontend/home-page-img/whatsapp-booking.png',
            'link' => 'https://wa.me/1234567890?text=Hi"',
            'bgClass' => 'border-2 border-[#00a884] bg-white text-[#00a884] hover:text-white hover:bg-[#00a884]',
            'textClass' => 'text-[#00a884]',
            'border' => 'border-[#00a884]'
        ],
        [
            'subtitle' => 'Appointment By',
            'title' => 'Hospitals',
            'buttonText' => 'Visit Now',
            'imgLink' => 'frontend/home-page-img/chember-2.webp',
            'link' => route('doctors.index'),
            'bgClass' => 'border-2 border-[#1db7d8] bg-white text-[#1db7d8] hover:text-white hover:bg-[#1db7d8]',
            'textClass' => 'text-[#1db7d8]',
            'border' => 'border-[#1db7d8]'
        ],
    ]
@endphp


<div class="mt-14 text-center px-4">
    <p class="mb-4 text-lg font-semibold text-[#39c9ba]">Get Appointment</p>
    <h3 class="title-font text-3xl">
        Other Appointment Process
    </h3>
</div>


<section class="bg-white pb-20 pt-8 px-4">
    <div class="container mx-auto md:flex flex-wrap grid grid-cols-2 justify-center gap-4">
        <div class="py-6 flex flex-col justify-between shadow-sm border border-[#fd5847] bg-white rounded-lg w-full md:w-[250px] relative overflow-hidden">
            <div>
                <img 
                    src="frontend/home-page-img/chember-1.webp" 
                    alt="" 
                    class="h-[80px] aspect-squar m-auto mb-2 z-30">
                <div class="text-center">
                    <h3 class="text-lg font-semibold max-md:text-sm pb-1">
                        Track Appointment
                    </h3>
                    <div class="mb-6 text-[#fd5847] px-5">
                        <input type="text" id="track_code" name="code" placeholder="Enter code" class="py-1 px-2 border border-[#fd5847] block w-full rounded-md focus:outline-none" />
                    </div>
                </div>
            </div>
            <button
                type="button"
                onclick="handleTrackRedirect()"
                class="bg-white text-[#fd5847] hover:bg-[#fd5847] border-2 border-[#fd5847] hover:text-white -mt-1.5 font-semibold text-sm m-auto max-md:py-1 max-md:px-2 px-4 py-2 rounded flex items-center justify-center max-md:w-[100px] md:w-[150px] gap-1 duration-300">
                Track Now
                <i class="text-white max-md:hidden font-bold ph ph-arrow-right"></i>
            </button>
        </div>
        @foreach ($data as $item)
            <div class="py-6 shadow-sm flex flex-col justify-between border {{ $item['border'] }} bg-white rounded-lg w-full md:w-[250px] relative overflow-hidden">
                <div>
                    <img 
                        src="{{ $item['imgLink'] }}" 
                        alt="" 
                        class="h-[80px] aspect-squar m-auto mb-2 z-30">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold max-md:text-sm pb-1">
                            {{ $item['subtitle'] }}
                        </h3>
                        <h4 class="text-xl font-extrabold max-md:font-[16px] mb-6 {{ $item['textClass'] }}">
                            {{ $item['title'] }}
                        </h4>
                    </div>
                </div>
                <a  
                    href={{ $item['link'] }}
                    class="{{$item['bgClass']}} font-semibold text-sm m-auto max-md:px-2 max-md:py-1 px-4 py-2 rounded flex items-center justify-center max-md:w-[100px] w-[150px] gap-1 duration-300">
                    {{ $item['buttonText'] }}
                    <i class="font-bold max-md:hidden ph ph-arrow-right"></i>
                </a>
            </div>
        @endforeach   
    </div>
</section>


<script>
const handleTrackRedirect = () => {
    let code = document.getElementById('track_code')
    if (code?.value) {
        location.href = `{{ url('') }}/track-appointment/${code.value}`
    }
}
</script>

{{-- <img src="@/img/chember-shap-2.png" alt="" class="absolute left-0 -top-10"> --}}
{{-- 
        <div class="py-6 shadow border border-black/10 bg-[white] rounded-lg w-[250px] relative overflow-hidden">
            <img src="@/img/chember-shap.png" alt="" class="absolute left-0 -bottom-10">
            <img src="@/img/icon-2.png" alt="" class="h-[80px] aspect-squar m-auto mb-2">
            <div class="text-center">
                <p class="text-lg font-semibold pb-1">Live Chat With</p>
                <h3 class="text-xl font-extrabold text-[#39c9ba] mb-6">Doctor</h3>
                <button
                    class="bg-[#39c9ba] text-white font-semibold text-sm shadow m-auto px-4 py-2 rounded-full flex items-center gap-1 hover:bg-black duration-300">
                    Visit Now
                    <i class="text-white font-bold ph ph-arrow-right"></i>
                </button>
            </div>
        </div> --}}

