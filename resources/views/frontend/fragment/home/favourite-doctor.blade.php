<section class="px-4 bg-[#ebf7f6] py-20">
    <div class="mb-10 text-center">
        <p class="mb-4 text-xl font-semibold text-[#39c9ba]">Feature List</p>
        <h3 class="title-font text-5xl max-md:text-3xl">Featured Doctors</h3>
    </div>

    <div class="container mx-auto grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($doctors as $item)
            <div class="relative flex flex-col justify-between rounded-lg shadow-sm bg-white">
                <div>
                    <a class="absolute right-4 top-4 grid aspect-square w-10 place-content-center rounded-full bg-[#eaf8f6] text-[#39c9ba] duration-300 hover:bg-[#061a3a]"
                        href="{{ route('doctor-profile.doctorProfile', $item->id) }}">
                        <i class="ph ph-eye text-lg font-semibold"></i>
                    </a>
                    <div class="flex justify-center mt-4">
                        <a href="{{ route('doctor-profile.doctorProfile', $item->id) }}">
                            <img class="w-[120px] object-cover object-center"
                                src="{{ asset(@$item->media->path ?? 'frontend/home-page-img/doctor_avatar.png') }}">
                        </a>
                    </div>
                    <div class="p-6 pb-3">
                        <div>
                            <a class="flex items-center gap-2 text-xl font-bold duration-300 hover:text-[#39c9ba]"
                                href="{{ route('doctor-profile.doctorProfile', $item->id) }}" title="{{ $item->name }}">
                            Dr. {{ str()->limit($item->name, 17) }}
                            </a>
                            <p class="py-2 text-sm font-medium text-black/70">
                                @php
                                    $specialization = collect($item->specializations)->map(fn($i) => @$i->specialization->name)->toArray();
                                @endphp
                                {{ implode(', ', $specialization) }}
                            </p>
                            @if (count($item->weekdays))
                                <p class="flex items-center font-semibold gap-2 py-2 text-sm text-black/70">
                                    <i class="ph-bold ph-calendar"></i>
                                    {{ implode(',  ', $item->weekdays) }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mb-6 pt-0 flex items-center justify-center">
                    <a class="rounded bg-[#39c9ba] px-4 py-2 font-medium text-white duration-300 hover:bg-black"
                        href="{{ route('book-appointment.index', [$item->id, str()->slug($item->name)]) }}">
                        Book Now
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-16">
        <a class="text-md m-auto flex max-w-[200px] items-center justify-center gap-1 rounded bg-[#39c9ba] px-6 py-3 font-semibold text-white shadow duration-300 hover:bg-black"
            href="{{ route('doctors.index') }}">
            All Doctor
            <i class="ph ph-arrow-right font-bold text-white"></i>
        </a>
    </div>
</section>
