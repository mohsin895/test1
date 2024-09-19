@extends('frontend.master')

@section('banner')
    <div class="h-[200px] grid place-content-center bg-[#39c9ba]/20 items-center">
        <h3 class="title-font text-5xl text-center">
            {{ $specialization->name }} Doctors List
        </h3>
    </div>
@endsection
@section('content')
    <div class="my-20">
        <div class="container mx-auto flex flex-wrap gap-3 justify-center">
            @forelse ($specialized_doctors as $specialized_doctor)
                <div class="bg-white rounded-lg shadow-xl border border-black/5 py-6 px-4">
                    <div class="grid md:grid-cols-2 justify-center  mb-4">
                        <img class="rounded-full w-[120px] mx-auto aspect-square object-cover border-4 border-black/20 shadow-lg"
                            src="{{ asset(@$specialized_doctor->doctor->media->path ?? 'frontend/home-page-img/doctor_avatar.png') }}">

                        <div class="p-3 justify-center grid ">
                            <h4 class="text-lg font-semibold truncate flex items-center mb-1"
                                title="Dr. {{ @$specialized_doctor->doctor->name }}">
                                <i class="text-[#39c9ba] text-lg ph ph-user"></i>
                                Dr. {{ Str::limit(@$specialized_doctor->doctor->name, 10) }}
                            </h4>
                            <h4 class="flex gap-2 items-center mb-1 font-medium">
                                <i class="text-[#39c9ba] text-lg ph ph-backpack"></i>
                                @foreach (@$specialized_doctor->doctor->designations as $designation)
                                    <h3>{{ @$designation->doctor_designation->name }}
                                        @unless ($loop->last)
                                            ,
                                        @endunless
                                    </h3>
                                @endforeach
                            </h4>
                            <h4 class="flex gap-1 items-center mb-1 font-medium">
                                <i class="text-[#39c9ba] ph ph-buildings"></i>
                                @foreach (@$specialized_doctor->doctor->specializations as $specialization)
                                    <h3>{{ @$specialization->specialization->name }}
                                        @unless ($loop->last)
                                            ,
                                        @endunless
                                    </h3>
                                @endforeach
                            </h4>
                            <h4 class="flex gap-1 items-center mb-1 font-medium">
                                <i class="text-[#39c9ba] text-lg ph ph-brain"></i>
                                {{ $specialized_doctor['experienceTitle'] }}
                            </h4>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h4 class="flex gap-2 items-center mb-1 font-semibold">
                            <i class="text-[#39c9ba] text-lg ph ph-certificate"></i>
                            {{-- {{ $specialized_doctor['specalTitle'] }} --}}
                        </h4>
                        <h4 class="flex gap-2 items-center mb-1 font-medium">
                            <i class="text-[#39c9ba] text-lg ph ph-map-pin"></i>
                            {{ $specialized_doctor['locationTitle'] }}
                        </h4>
                    </div>

                    <div class="flex items-center justify-center flex-wrap gap-4">
                        <a class="border-[#39c9ba]/70 border text-black shadow px-3 py-1 rounded hover:bg-[#39c9ba] hover:text-white duration-300"
                            href="{{ route('doctor-profile.doctorProfile', @$specialized_doctor->doctor->id) }}">
                            VIEW PROFILE
                        </a>
                        <a class="hover:border-[#39c9ba]/70 border text-black shadow px-3 py-1 rounded  bg-[#39c9ba] hover:text-white duration-300"
                            href="{{ route('book-appointment.index', [@$specialized_doctor->doctor->id, str()->slug(@$specialized_doctor->doctor->name)]) }}">
                            BOOK Now
                        </a>
                    </div>

                </div>
            @empty
                <span class="text-2xl text-red-600">No Records found for this specialty...</span>
            @endforelse
        </div>

        <div class="mt-16">
            {{ $specialized_doctors->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endsection
