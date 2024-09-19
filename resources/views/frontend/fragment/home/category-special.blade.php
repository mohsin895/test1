<section class="relative bg-[#ebf7f6] py-20 px-4">
    <div class="mb-10 text-center">
        <p class="mb-4 text-lg font-semibold text-[#39c9ba]">CATEGORY</p>
        <h3 class="title-font text-5xl max-md:text-3xl">Browse by specializations</h3>
    </div>

    <div class="z-10 mx-auto max-md:grid grid-cols-2 md:flex container flex-wrap items-center justify-center gap-4">
        @foreach ($specializations as $item)
            <div class="w-full md:w-[250px] rounded-lg border border-black/10 bg-white max-sm:py-5 py-10 shadow-sm">
                <img alt="No Image" class="aspect-squar m-auto mb-4 h-[60px]"
                    src="{{ asset(@$item->media->path ?? 'images/no-image.png') }}">
                <div class="z-10 text-center">
                    <h4 class="pb-4 text-xl max-sm:text-sm   font-semibold">
                        {{ $item['name'] }}
                    </h4>
                    <a class="m-auto flex max-sm:w-[100px] w-[150px] items-center justify-center gap-1 rounded bg-[#39c9ba] max-sm:py-1 max-sm:px-2 px-4 py-2 text-sm font-semibold text-white shadow duration-300 hover:bg-black"
                        href="{{ route('specialized.doctors', $item->id) }}">
                        View List
                        <i class="ph ph-arrow-right font-bold text-white"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    {{-- 
    <div class="mt-16">
        <a class="text-md m-auto flex max-w-[200px] items-center justify-center gap-1 rounded bg-[#39c9ba] px-6 py-3 font-semibold text-white shadow duration-300 hover:bg-black"
            href="#">
            All Category
            <i class="ph ph-arrow-right font-bold text-white"></i>
        </a>
    </div> --}}
    <img alt="" class="absolute bottom-0 left-0 pointer-events-none" src="frontend/home-page-img/catagory-absolute.webp">
    <img alt="" class="absolute right-0 top-0 pointer-events-none" src="frontend/home-page-img/catagory-absolute.webp">
</section>
