@php
    $data = [
        [
            'title' => "Visit Doctor's",
            'subtitle' => 'Profile',
            'imgLink' => 'frontend/home-page-img/appoint-1.webp'
        ],
        [
            'title' => 'Get Instant',
            'subtitle' => 'Appointment',
            'imgLink' => 'frontend/home-page-img/appoint-2.webp'
        ],
        [
            'title' => "Track Doctor's",
            'subtitle' => 'Schedule',
            'imgLink' => 'frontend/home-page-img/appoint-3.webp'
        ]
    ];
@endphp



<section class="pt-20 px-4 relative">
    <div class="text-center mb-10">
        <h3 class="text-5xl max-md:text-3xl title-font">How It Works?</h3>
    </div>

    <div class="container mx-auto flex flex-wrap justify-center gap-x-[150px] relative mb-10">
        <img
          src="frontend/home-page-img/appoint-absolute.webp" 
          alt="" 
          class="absolute top-12 max-md:hidden">
        @foreach ($data as $item)
          <div class="w-[250px] z-10 bg-white mb-10">
            <div class="px-4 py-8 shadow border border-black/5 rounded-2xl mb-6">
                <img
                  src="{{ $item['imgLink'] }}" 
                  alt="" 
                  class="mx-auto"
                >
            </div>
            <h3 class="text-xl font-extrabold pb-1 grid text-center">
                {{ $item['title'] }}
            </h3>
            <h4 class="text-lg font-extrabold grid text-center">
              {{$item['subtitle']}}
            </h4>
          </div>
        @endforeach
    </div>
</section>
