@php
$mahsin = [
    [
        "imgLink" => "frontend/home-page-img/petner-1.webp",
    ],
    [
        "imgLink" => "frontend/home-page-img/petner-1.webp",
    ],
    [
        "imgLink" => "frontend/home-page-img/petner-1.webp",
    ],
    [
        "imgLink" => "frontend/home-page-img/petner-1.webp",
    ],
];
@endphp

<section class="py-10">
    <div class="container mx-auto flex flex-wrap gap-20 justify-center">
        @foreach ($mahsin as $item)
            <!-- <div>
                <img 
                    src="{{ $item['imgLink'] }}" 
                    alt=""
                    class="h-20"
                >
            </div> -->
        @endforeach
    </div>
</section>