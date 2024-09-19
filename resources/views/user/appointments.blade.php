@extends('frontend.master')

@section('bg_banner')
<div class="py-20 container mx-auto text-center text-4xl title-font font-bold">
    <h2>My Appointments</h2>
</div>
@endsection

@section('content')
<div class="px-4">
    <div class="py-20 container mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($appointments as $item)
                <div class="border shadow-sm p-5 rounded">
                    <div class="font-semibold text-2xl">{{ $item->patient_name }}</div>
                    <div><span class="font-semibold inline-block w-[60px]">Phone: </span> {{ $item->phone }}</div>
                    <div><span class="font-semibold inline-block w-[60px]">Date: </span>{{ $item->date }}</div>
                    <div><span class="font-semibold inline-block w-[60px]">Code: </span>{{ $item->tracking_code }}</div>
                    <a href="{{ route('appointmentTrack', $item->tracking_code) }}" class="py-1 text-center mt-4 inline-block px-4 bg-primary text-white rounded">View details</a>
                </div>
            @endforeach
        </div>
        @if(!count($appointments))
            <div class="text-center text-red-400 text-5xl font-bold py-10">
                No record found
            </div>
        @endif
    </div>
</div>
@endsection