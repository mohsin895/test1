@extends('frontend.master')

@section('bg_banner')
    <div class="h-[200px] grid place-content-center items-center">
        <h3 class="title-font text-5xl text-center">
            Hospitals
        </h3>
    </div>
@endsection
@section('content')
    <div class="mb-20 mt-10">
        <form id="filterForm" method="GET" class="container mx-auto flex flex-wrap justify-center items-center mb-10 ">
            <div class="mr-5 font-semibold">
                Select divisions
            </div>
            <div class="flex flex-wrap gap-3">
                @foreach (BD_DIVISIONS as $division)
                    <label>
                        <input oninput="filterForm()" type="checkbox" name="divisions[]" value="{{ $division }}" @if(in_array($division, $selected_divisions)) checked @endif />
                        {{ $division }}
                    </label>
                @endforeach
            </div>
        </form>
        
        <div class="container mx-auto flex flex-wrap gap-5 justify-center">
            @foreach ($hospitals as $hospital)
                <div class="bg-white w-[300px] md:w-[350px] rounded-lg shadow border border-black/5 py-6 px-4">
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
                        <div class="flex justify-end">
                            <a class="hover:border-[#39c9ba]/70 border text-white shadow px-3 py-1 rounded  bg-[#39c9ba] hover:text-white duration-300"
                                href="{{ route('hospital_details', ['id' => $hospital->id, 'slug' => str()->slug($hospital->name)]) }}">
                                View doctors
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(!count($hospitals))
            <div class="font-bold text-2xl text-red-500 text-center py-10">
                No Doctor Found!
            </div>
        @endif
        
        <div class="mt-16">
            {{ $hospitals->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <script>
        const filterForm = () => {
            document.getElementById('filterForm').submit()
        }
    </script>
@endsection
