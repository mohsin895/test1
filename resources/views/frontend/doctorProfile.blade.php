@extends('frontend.master')

@section('banner')
@endsection
@section('content')
    <div class='bg-[#ebf7f6]/40 px-4 py-20'>
        <div class='container mx-auto mb-6 gap-10 md:flex'>
            <div class="flex-2">
                <div class="mb-4 grid justify-center">
                    <div class="flex items-center justify-center ">
                        <img alt="" class="mb-4 aspect-square max-w-[250px] rounded-full object-cover"
                            src="{{ asset(@$doctorProfile->media->path ?? 'frontend/home-page-img/doctor_avatar.png') }}">
                    </div>
                    <h3 class="mb-2 flex items-center justify-center text-xl font-semibold">
                        <i class="ph ph-user text-2xl font-semibold text-[#39c9ba]"></i>
                        Dr. {{ $doctorProfile->name }}
                    </h3>
                    <h3 class="flex items-center justify-center font-medium">
                        <i class="ph ph-buildings text-2xl font-semibold text-[#39c9ba]"></i>
                        {{-- Internal Medicine  --}}
                        @foreach ($doctorProfile->designations as $designation)
                            {{ $designation['doctor_designation']['name'] }}
                            @unless ($loop->last)
                                ,
                            @endunless
                        @endforeach
                    </h3>
                    <h3 class="flex items-center justify-center font-medium">
                        <i class="ph ph-buildings text-2xl font-semibold text-[#39c9ba]"></i>
                        {{-- Internal Medicine  --}}
                        @foreach ($doctorProfile->specializations as $specialization)
                            {{ $specialization['specialization']['name'] }}
                            @unless ($loop->last)
                                ,
                            @endunless
                        @endforeach
                    </h3>
                </div>
            </div>
            <div class="flex-1">
                <div class="grid gap-4 lg:grid-cols-2">
                    <div>
                        <div class="">
                            <div class="mb-4 border px-4 py-8">
                                <h3 class="mb-1 flex items-center gap-4 text-xl font-semibold">
                                    <i class="ph ph-graduation-cap text-2xl font-semibold text-[#39c9ba]"></i>
                                    Degrees and Trainings
                                </h3>
                                <p class="mb-2 flex items-center mx-4 font-medium">
                                    @foreach ($doctorProfile->degrees as $doctorDegree)
                                        {{ $doctorDegree->doctor_degrees->name }}
                                        @unless ($loop->last)
                                            ,
                                        @endunless
                                    @endforeach
                                </p>
                            </div>
                            <div class="mb-4 border px-4 py-8">
                                <h3 class="mb-1 flex items-center gap-4 text-xl font-semibold">
                                    <i class="ph ph-certificate text-2xl font-semibold text-[#39c9ba]"></i>
                                    Specialization
                                </h3>
                                <p class="mb-2 flex items-center mx-4 font-medium">
                                    {{-- Internal Medicine & Diabetologist  --}}
                                    @foreach ($doctorProfile->specializations as $specialization)
                                        {{ @$specialization->specialization->name }}
                                        @unless ($loop->last)
                                            ,
                                        @endunless
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        <!-- <div class="w-full border border-black/10 p-4 shadow">                                                                                                                                                                                                                                          </div> -->
                    </div>

                    <div>
                        <div class="">
                            <div class="mb-4 border px-4 py-8">
                                <h3 class="mb-1 flex items-center gap-4 text-xl font-semibold">
                                    <i class="ph ph-calendar-check text-2xl font-semibold text-[#39c9ba]"></i>
                                    Visitation Day
                                </h3>
                                <p class="mb-2 flex items-center mx-4 gap-2 font-medium">
                                    @foreach ($doctorProfile->doctor_chambers as $chamber)
                                        @foreach ($chamber['weekdays'] as $weekday)
                                            {{ ucfirst(substr($weekday, 0, 3)) }}
                                            @unless ($loop->last)
                                                ,
                                            @endunless
                                        @endforeach
                                    @endforeach
                                </p>
                            </div>
                            <div class="mb-4 border px-4 py-8">
                                <h3 class="mb-1 flex items-center gap-4 text-xl font-semibold">
                                    <i class="ph ph-currency-circle-dollar text-2xl font-semibold text-[#39c9ba]"></i>
                                    Visitation Fee
                                </h3>
                                <div class="mb-2 flex max-w-[220px] mx-4 items-center gap-2 leading-7">
                                    @foreach ($patientTypes as $type)
                                        {{ $type->name }}
                                        @if ($type->name === 'New Patient')
                                            ({{ @$doctorProfile->doctor_details->new_fees }} BDT)
                                        @elseif($type->name === 'Old Patient')
                                            ({{ @$doctorProfile->doctor_details->old_fees }} BDT)
                                        @else
                                            ({{ @$doctorProfile->doctor_details->report_fees }} BDT)
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="grid gap-6 mt-6 md:grid-cols-2">
                                <a class="flex items-center gap-4 rounded border border-[#39c9ba] px-2 py-2.5 font-medium text-[#39c9ba] hover:bg-[#39c9ba] hover:text-white"
                                    href="#">
                                    <i class="ph ph-phone-call text-2xl font-semibold"></i>
                                    HELP LINE
                                </a>
                                <a class="flex items-center gap-4 rounded border bg-[#39c9ba] px-2 py-2.5 font-medium text-white"
                                    href="{{ route('book-appointment.index', [$doctorProfile->id, $doc_slug]) }}">
                                    <i class="ph ph-notepad text-2xl font-semibold text-white"></i>
                                    BOOK APPOINTMENT
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-center">
            @php
                $specializationIds = $doctorProfile->specializations->pluck('specialization_id')->toArray();
        
            @endphp

            <a class="mx-auto rounded border bg-[#39c9ba] px-4 py-2 text-center font-medium text-white"
                href="{{ route('related-doctors', 'specialization=' .''. implode(',', $specializationIds)) }}">
                See Related Speciality Doctors
            </a>
        </div>
    </div>
@endsection
