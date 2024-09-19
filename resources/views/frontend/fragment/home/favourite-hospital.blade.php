<section class="py-20 bg-slate-50 px-4">
    <div class="mb-10 text-center">
        <h3 class="title-font text-5xl max-md:text-3xl">Featured Hospital</h3>
    </div>

    <div class="container mx-auto flex flex-wrap gap-5 justify-center">
        @foreach ($featured_hospitals as $hospital)
            <div class="bg-white w-[300px] md:w-[350px] rounded-lg shadow border border-black/5 max-md:py-4 py-6 px-4">
                <div class="p-3 justify-center">
                    <h4 class="text-lg font-semibold truncate flex gap-2 items-center"
                        title="{{ $hospital->name }}">
                        <i class="text-[#39c9ba] text-lg ph-bold ph-buildings"></i>
                        {{ Str::limit($hospital->name, 25) }}
                    </h4>
                    <div>
                        {{ $hospital->city }}, {{ $hospital->division }}    
                    </div>
                    <div>
                        {{ Str::limit($hospital->address, 50) }}
                    </div>
                    <div class="flex justify-end mt-3 max-md:justify-center">
                        <a class="hover:bg-black border text-white shadow px-3 py-1 rounded  bg-[#39c9ba] hover:text-white duration-300"
                            href="{{ route('hospital_details', ['id' => $hospital->id, 'slug' => str()->slug($hospital->name)]) }}">
                            View doctors
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-16">
        <a class="text-md m-auto flex max-w-[200px] items-center justify-center gap-1 rounded bg-[#39c9ba] px-6 py-3 font-semibold text-white shadow duration-300 hover:bg-black"
            href="{{ route('hospitals') }}">
            All Hospitals
            <i class="ph ph-arrow-right font-bold text-white"></i>
        </a>
    </div>
</section>
