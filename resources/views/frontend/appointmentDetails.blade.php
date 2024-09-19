@extends('frontend.master')

@section('banner')
@endsection

@section('content')
    <div class=" max-w-[1050px] mx-auto mb-10 px-4 sm:px-4">
        <div class="mt-10">
            <div class="mb-10">
                <img 
                    src="https://tpc.googlesyndication.com/simgad/1507480816944749431"
                    alt=""
                    class="w-full h-[70px] sm:h-[90px]"
                >
            </div>
            @if (@$track)
                @if (@$track->current_serial == 'Emergency' || @$track->current_serial == 'Break' || @$track->current_serial == 'Prayer')  
                    <div class="px-4 py-6 border border-red-500 rounded bg-red-100 block gap-6 items-center relative mb-10 sm:flex">
                        <div class="grid place-content-center">
                            <svg class="fill-current text-red-500" xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" viewBox="0 0 256 256"><path d="M232,136.66A104.12,104.12,0,1,1,119.34,24,8,8,0,0,1,120.66,40,88.12,88.12,0,1,0,216,135.34,8,8,0,0,1,232,136.66ZM120,72v56a8,8,0,0,0,8,8h56a8,8,0,0,0,0-16H136V72a8,8,0,0,0-16,0Zm40-24a12,12,0,1,0-12-12A12,12,0,0,0,160,48Zm36,24a12,12,0,1,0-12-12A12,12,0,0,0,196,72Zm24,36a12,12,0,1,0-12-12A12,12,0,0,0,220,108Z"></path></svg>
                        </div>
                        <div>
                            <h5 class="mb-2 text-xl font-semibold text-center text-black/70 sm:text-left">
                                {{ $track->current_serial }} For <span class="text-[#fd5847] text-2xl">{{ $track->break_duration }}</span> Minutes
                            </h5>
                            <p class="text-center text-black/70 sm:text-left">
                                The {{ $track->current_serial }} time is supposed to end at 
                                <span class="text-[#fd5847] font-semibold">{{ $track->end_at }}</span>
                            </p>
                        </div>
                    </div>
                @else
                    <div class="px-4 py-6 border border-[#39c9ba] rounded bg-[#39c9ba22] block gap-6 items-center relative mb-10 sm:flex">
                        <div>
                            <img src="/frontend/home-page-img/doct.webp" alt="" class="w-[80px] mx-auto sm:mx-0">
                        </div>
                        <div>
                            <h5 class="mb-2 text-xl font-semibold text-center text-black/70 sm:text-left">
                                Visitation has been started.
                            </h5>
                            <p class="text-center text-black/50 sm:text-left">
                                Doctor has started the visitation already. Please check this page to know the updates continuously.
                            </p>
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center font-bold mb-5">
                    <div class="px-4 py-6 border border-[#51ace3] rounded bg-[#51ace322] block gap-6 items-center relative mb-10 sm:flex">
                        <div class="grid place-content-center">
                            <svg class="fill-current text-[#51ace3]" xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" viewBox="0 0 256 256"><path d="M216,68H172V56a20,20,0,0,0-20-20H104A20,20,0,0,0,84,56V68H40A12,12,0,0,0,28,80V208a12,12,0,0,0,12,12H216a12,12,0,0,0,12-12V80A12,12,0,0,0,216,68ZM92,56a12,12,0,0,1,12-12h48a12,12,0,0,1,12,12V68H92ZM220,208a4,4,0,0,1-4,4H40a4,4,0,0,1-4-4V80a4,4,0,0,1,4-4H216a4,4,0,0,1,4,4Zm-64-64a4,4,0,0,1-4,4H132v20a4,4,0,0,1-8,0V148H104a4,4,0,0,1,0-8h20V120a4,4,0,0,1,8,0v20h20A4,4,0,0,1,156,144Z"></path></svg>
                        </div>
                        <div>
                            <h5 class="mb-2 text-xl font-semibold text-center text-black/70 sm:text-left">
                                Visitation is not started yet!
                            </h5>
                            <p class="text-center text-black/50 font-normal sm:text-left">
                                Doctor hasn't started the visitation yet. You will be notified when the doctor will start the visitation once.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($changed_time)
                <div class="text-center font-bold mb-5">
                    <div class="px-4 py-6 border border-[#fd5847] rounded bg-[#fd584722] block gap-6 items-center relative mb-10 sm:flex">
                        <div class="grid place-content-center">
                            <svg class="fill-current text-[#fd5847]" xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#ffffff" viewBox="0 0 256 256"><path d="M236.8,188.09,149.35,36.22h0a24.76,24.76,0,0,0-42.7,0L19.2,188.09a23.51,23.51,0,0,0,0,23.72A24.35,24.35,0,0,0,40.55,224h174.9a24.35,24.35,0,0,0,21.33-12.19A23.51,23.51,0,0,0,236.8,188.09ZM222.93,203.8a8.5,8.5,0,0,1-7.48,4.2H40.55a8.5,8.5,0,0,1-7.48-4.2,7.59,7.59,0,0,1,0-7.72L120.52,44.21a8.75,8.75,0,0,1,15,0l87.45,151.87A7.59,7.59,0,0,1,222.93,203.8ZM120,144V104a8,8,0,0,1,16,0v40a8,8,0,0,1-16,0Zm20,36a12,12,0,1,1-12-12A12,12,0,0,1,140,180Z"></path></svg>
                        </div>
                        <div>
                            <h5 class="mb-2 text-xl font-semibold text-center text-[#fd5847] sm:text-left">
                                Visitation schedule changed!
                            </h5>
                            <p class="text-center text-[#fd5847] font-normal sm:text-left">
                                Visitation schedule has be changed <span class="font-semibold">{{ $changed_time }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid items-center justify-center grid-cols-2 gap-4 sm:grid-cols-2">
                <div class="w-full p-4 border border-[#57c8f3] rounded shadow min-h-[100px]">
                    <img src="frontend/home-page-img/appoint-1.webp" class="w-[80px] mx-auto" alt="">
                    <div id="last_serial" class="text-[50px] font-semibold text-[#51ace3] text-center">
                        {{ @$track->last_serial ?? '--' }}
                    </div>
                    <h3 class="text-lg font-semibold text-center text-slate-700">
                        @if (!isset($track))
                            Not Started
                        @else
                            @if (!@$track->current_serial)
                                Starting
                            @else
                                {{ is_int(@$track->current_serial) ? 'Ongoing Serial No' : 'Last Serial No' }}
                            @endif
                        @endif
                    </h3>
                </div>
                <div class="w-full p-4 border border-[#57c8f3] rounded shadow min-h-[100px]">
                    <img src="frontend/home-page-img/appoint-2.webp" class="w-[80px] mx-auto" alt="">

                    @if (@$track->current_serial)
                        @if (is_int((int)$track->current_serial))
                            <div id="your_serial" class="text-[50px] font-semibold {{ (int)$track->current_serial > (int)$appointment->serial_no ? 'text-[#fd5847]' : 'text-[#39c9ba]' }} text-center">
                                {{ $appointment->serial_no }}
                            </div>
                        @else
                            <div id="your_serial" class="text-[50px] font-semibold text-[#fd5847] text-center">
                                {{ $appointment->serial_no }}
                            </div>
                        @endif
                    @else
                        <div id="your_serial" class="text-[50px] font-semibold text-[#39c9ba] text-center">
                            {{ $appointment->serial_no }}
                        </div>
                    @endif
                    <h3 class="text-lg font-semibold text-center text-slate-700">Your Serial No</h3>
                </div>

                
                {{-- <div class="w-full p-4 border rounded shadow min-h-[230px]">
                    <img src="frontend/home-page-img/chember-1.webp" class="w-[80px] mx-auto" alt="">
                    <div class="text-[50px] font-semibold text-[#39c9ba] text-center">10<span class="text-[18px]">Min</span></div>
                    <h3 class="text-lg font-semibold text-center text-black/70">Average Waiting Time</h3>
                </div> --}}
            </div>
            {{-- <div class="mt-5 mb-5">
                <img 
                    src="https://tpc.googlesyndication.com/simgad/5697156337081672690"
                    alt=""
                    class="w-full h-[70px] sm:h-[90px]"
                >
            </div> --}}


            <div class="py-4 mb-10 mt-8">
                <h3 class="text-2xl font-semibold text-center mb-5 text-slate-700">Maps Indicator</h3>
                <div class="grid items-center justify-center grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="w-full p-4 border border-[#f3948a] rounded shadow min-h-[100px]">
                        <img src="frontend/home-page-img/appoint-1.webp" class="w-[80px] mx-auto" alt="">
                        <div id="distance" class="text-[50px] font-semibold text-[#fd5847] text-center">
                            --
                        </div>
                        <h3 class="text-lg font-semibold text-center text-slate-700">Your Distance</h3>
                    </div>
                    <div class="w-full p-4 border border-[#f3948a] rounded shadow min-h-[100px]">
                        <img src="frontend/home-page-img/appoint-1.webp" class="w-[80px] mx-auto" alt="">
                        <div id="duration" class="text-[50px] font-semibold text-[#fd5847] text-center">
                            --
                        </div>
                        <h3 class="text-lg font-semibold text-center text-slate-700">Your Duration</h3>
                    </div>
                </div>
                {{-- <p class="text-center text-black/70">Your Currently <b>3KM</b> away from the Chember. It may take <b>1 Hours 10 Mins</b> to reach the chember</p>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In libero adipisci, nesciunt provident error alias quod laborum qui, minima corrupti ipsam quae fugit omnis sapiente tenetur, nemo odio voluptates modi!
                </p>
                <p class="text-center text-black/70">Your Currently <b>3KM</b> away from the Chember. It may take <b>1 Hours 10 Mins</b> to reach the chember</p> --}}
            </div>


            <div class="">
                <h3 class="mb-4 text-2xl font-semibold text-center text-slate-700">Ch. Location</h3>
                <div class="h-[250px] sm:h-[350px]">
                    {{-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d28985.715496429137!2d90.4031032!3d24.7538358!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1699468220136!5m2!1sen!2sbd" width="100%" height="100%" style="border:0; border-radius: 5px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> --}}
                    <iframe src="https://maps.google.com/maps?q={{ @$appointment->doctor_chamber->chamber->lat }},{{ @$appointment->doctor_chamber->chamber->lon }}&z=19&output=embed" height="400" class='w-full' allowFullScreen="" loading="lazy" referrerPolicy="no-referrer-when-downgrade"></iframe>
                    {{-- <iframe src="https://maps.google.com/maps?q=24.7525992,90.3933728&z=19&output=embed" height="400" class='w-full' allowFullScreen="" loading="lazy" referrerPolicy="no-referrer-when-downgrade"></iframe> --}}
                </div>
            </div>

            <div class="mt-5 mb-5">
                <img 
                    src="https://tpc.googlesyndication.com/simgad/5142142042275484949"
                    alt=""
                    class="w-full h-[70px] sm:h-[90px]"
                >
            </div>
            @if ($appointment->media_file)
                @php
                    // $file_type = null;
                    // try {
                    //     $pathinfo = pathinfo($appointment->media_file->path);
                    //     dd($pathinfo['extension']);
                    // } catch (\Throwable $th) {}
                @endphp
                <div class="mt-10">
                    <h3 class="mb-4 text-2xl font-semibold text-center text-slate-700">
                        My Prescription
                    </h3>
                    <div class="h-[250px] sm:h-[350px]">
                        <img 
                            src="{{ asset($appointment->media_file->path) }}"
                            alt=""
                            class="max-w-full"
                        />
                    </div>
                </div>
            @endif
        </div>
    </div>
    

<script>

function getLocation() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                // Get latitude and longitude
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
            
                const distance_url = `{{ route('getDistance') }}`
                let distance = document.getElementById('distance')
                let duration = document.getElementById('duration')
                let formData = new FormData();
                formData.append('_token', `{{ csrf_token() }}`)
                formData.append('lat', latitude)
                formData.append('lon', longitude)
                formData.append('code', `{{ $appointment->tracking_code }}`)
                fetch(distance_url, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(result => {
                    distance.innerHTML = result?.distance
                    duration.innerHTML = result?.duration
                })

            },
            function(error) {
                // Handle errors
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                    console.error("User denied the request for geolocation.");
                    break;
                    case error.POSITION_UNAVAILABLE:
                    console.error("Location information is unavailable.");
                    break;
                    case error.TIMEOUT:
                    console.error("The request to get user location timed out.");
                    break;
                    case error.UNKNOWN_ERROR:
                    console.error("An unknown error occurred.");
                    break;
                }
            }
        );
    }
}

getLocation()

setInterval(() => {
    getLocation()
}, 30 * 1000)


function getSerial() {
    let url = `{{ route('appointmentTrackAjax', $appointment->tracking_code) }}`

    fetch(url)
        .then(res => res.json())
        .then(result => {
            let your_serial = document.getElementById('your_serial')
            let last_serial = document.getElementById('last_serial')

            if (result.appointment?.serial_no) {
                your_serial.innerHTML = result.appointment?.serial_no
            }
            if (result.track?.last_serial) {
                last_serial.innerHTML = result.track?.last_serial
            }
        })
}
getSerial()
setInterval(() => {
    getSerial()
}, 10 * 1000)

</script>
@endsection

