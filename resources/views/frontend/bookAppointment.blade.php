@extends('frontend.master')

@section('bg_banner')
    <div class="py-20 text-center text-white text-2xl font-bold">
        <h2>Book Appoinment</h2>
    </div>
@endsection

@section('content')
    <div class="bg-[#ebf7f6] py-16 relative z-10 p-4 overflow-hidden">
        <div class="container mx-auto">
            <div class="mb-4">
                <h3 class="text-2xl font-bold text-center">
                    Book Appointment for {{ $doctor->name }}
                </h3>
            </div>
            <div class="max-w-[955px] mx-auto px-6">
                @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 border border-red-600 rounded-lg bg-red-50" role="alert">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('book-appointment.store', $doctor->id) }}" method="post">
                    @csrf
                    <div class="">

                        <div class="mb-4 grid md:grid-cols-2 gap-4">
                            <div class="">
                                <h3 class="text-lg font-semibold mb-4">
                                    Patient Name:
                                </h3>
                                <input
                                    class="px-5 py-2 w-full placeholder:text-black/70 bg-white shadow rounded outline-none"
                                    id="patient_name" name="patient_name" placeholder="Your Name..." required type="text"
                                    value="{{ old('patient_name') }}" />
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-4">
                                    Mention the patient's Number:
                                </h3>
                                <input
                                    class="px-5 py-2 w-full placeholder:text-black/70 bg-white shadow rounded outline-none"
                                    id="phone" name="phone" placeholder="Phone Number..." required type="text"
                                    value="{{ old('phone') }}" />
                            </div>
                        </div>

                        <div class="mb-4 grid md:grid-cols-2 gap-x-4">
                            <div>
                                <h3 class="text-lg font-semibold mb-4">
                                    Mention the patient's age:
                                </h3>
                                <input
                                    class="px-5 py-2 w-full placeholder:text-black/70 bg-white shadow rounded outline-none"
                                    id="age" name="age" placeholder="Age..." required type="text"
                                    value="{{ old('age') }}" />
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">
                                    Patient Type:
                                </h3>
                                <div class="flex flex-wrap gap-x-4 items-center">
                                    @foreach ($types as $key => $type)
                                        <label class="flex items-center gap-2 py-1 font-medium text-black/70">
                                            <input class="w-4 aspect-square" name="patient_type" oninput="setTypeId(this)"
                                                required type="radio"
                                                value="{{ old('patient_type') ? old('patient_type') : $type->id }}" />

                                            {{ $type->name }}

                                            (@if ($type->name == 'New Patient')
                                                {{ @$doctor->doctor_details->new_fees }}
                                            @elseif($type->name == 'Old Patient')
                                                {{ @$doctor->doctor_details->old_fees }}
                                            @else
                                                {{ @$doctor->doctor_details->report_fees }}
                                            @endif BDT)
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-4">
                                Select Chamber:
                            </h3>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                                @foreach ($doctor->doctor_chambers as $doctor_chamber)
                                    <label class="flex items-center gap-2 font-medium text-black/70">
                                        <input class="w-4 aspect-square chamber_id" name="chamber" type="radio"
                                            value="{{ $doctor_chamber->id }}">
                                        {{ @$doctor_chamber->chamber->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">
                                Doctor Present Date :
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 justify-center gap-5" id="date_grid">
                                <div class="col-span-3">Select a chamber first to see available dates.</div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">
                                Available Slot(s) :
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 justify-center gap-5 min-h-[42px]" id="slot_grid">
                                <div class="col-span-3">Select a date first to see available slots.</div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button
                                class="w-full text-center text-lg font-meduim bg-[#39c9ba] hover:bg-[#39c9ba]/50 mx-auto py-1 px-6 rounded shadow text-white mt-5">
                                Book Now
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div>
            <img alt="" class="absolute top-0 right-0 pointer-events-none"
                src="frontend/home-page-img/catagory-absolute.webp">
            <img alt="" class="absolute bottom-0 left-0 pointer-events-none"
                src="frontend/home-page-img/catagory-absolute.webp">
            <img alt="" class="absolute bottom-[50%] left-[40%] pointer-events-none "
                src="frontend/home-page-img/catagory-absolute.webp">
        </div>
    </div>
    <script src="{{ asset('frontend/js/axios.min.js') }}"></script>
    <script>
        let chamber_list = document.querySelectorAll('.chamber_id')
        const doctor_id = `{{ $doctor->id }}`
        const date_grid = document.getElementById('date_grid')
        const age = document.getElementById('age')
        const phone = document.getElementById('phone')
        const patient_name = document.getElementById('patient_name')

        let patient_type_id =
            `@if (count($types)) {{ $types[0]->id }} @endif`

        function setTypeId(item) {
            patient_type_id = event.target.value
        }

        function convert24to12(time24) {
            const [hours, minutes] = time24.split(':');
            const hours24 = parseInt(hours, 10);
            const period = hours24 >= 12 ? 'PM' : 'AM';
            const hours12 = hours24 % 12 || 12;
            const time12 = `${hours12}:${minutes} ${period}`;
            return time12;
        }

        const getFromTime = (item) => {
            let from_time = item.from_time
            try {
                if (item.modifier.length) {
                    let last_modified_item = item.modifier[item.modifier.length-1]
                    from_time = last_modified_item.from_time
                }
            } catch (error) {}
            return from_time
        }

        const renderDates = (dates = [], _chamber_id) => {
            let grid_html = ''
            dates.forEach(item => {
                let off_day_text = item.full_off_day ?
                    `<span class="absolute text-[10px] bg-red-500 text-white py-0.5 px-2 rounded-full -top-3 right-2">Off Day</span>` :
                    ''
                grid_html += `
                    <label class="block cursor-pointer relative">
                        ${off_day_text}
                        <input
                            type="radio" 
                            name="date" 
                            hidden
                            class="peer"
                            value="${item._date}"
                            ${item.full_off_day ? 'disabled' : ''}
                            onchange="handleSlot('${item._date}', '${item.day}', ${_chamber_id})"
                        >
                        <span 
                            class="bg-white block shadow py-2 px-5 rounded border-[#39c9ba] peer-checked:bg-[#39c9ba] peer-disabled:bg-red-100 border peer-disabled:cursor-not-allowed peer-disabled:border-red-500 peer-checked:text-white select-none truncate"
                            title="${item.date}"
                        >
                            ${item.date}
                        </span>
                    </label>
                `
            })
            date_grid.innerHTML = grid_html
        }

        const renderSlots = (data = []) => {
            let slot_html = ''
            data.forEach(item => {
                
                let limit = `
                    <div class="flex gap-1">
                        <span>${item.booked}</span> / 
                        <span>${item.limit}</span>
                    </div>
                `

                slot_html += `
                    <label class="block ${item.off_slot || item.booked == item.limit ? 'cursor-not-allowed' : 'cursor-pointer'} relative">
                        ${
                            item.off_slot ? 
                                '<span class="absolute -top-3 right-2 text-[10px] bg-red-500 text-white inline-block py-1 px-2 rounded-full">Off</span>'
                                 : item.booked == item.limit 
                                    ? '<span class="absolute -top-3 right-2 text-[10px] bg-red-500 text-white inline-block py-1 px-2 rounded-full">Unavailable</span>'
                                        : ''
                            }
                        <input 
                            type="radio" 
                            name="slot" 
                            hidden
                            class="peer"
                            value="${item.id}"
                            ${item.off_slot || item.booked == item.limit ? 'disabled' : ''}
                        >
                        <span 
                            class="bg-white ${item.off_slot || item.booked == item.limit ? 'bg-red-100 border-red-500' : 'border-[#39c9ba]'} border peer-checked:border-[#39c9ba] flex justify-between items-center shadow py-2 px-5 rounded peer-checked:bg-[#39c9ba] peer-checked:text-white select-none truncate"
                            title="Slots"
                        >
                            ${convert24to12(getFromTime(item))} - ${convert24to12(item.to_time)}
                            ${item.off_slot ? '' : limit}
                        </span>
                    </label>
                `
            })
            slot_grid.innerHTML = slot_html
        }

        let links = `{{ route('book-appointment.getDates', $doctor->id) }}`
        if (chamber_list.length) {
            chamber_list.forEach(item => {
                item.addEventListener('change', function(event) {
                    let chamber_id = event.target.value
                    // date_grid.innerHTML = ''
                    slot_grid.innerHTML =
                        '<div class="col-span-3">Select a date first to see available slots.</div>'

                    axios.get(links)
                        .then(res => {
                            renderDates(res.data || [], chamber_id)
                        })
                })
            })
        }

        let slot_link = `{{ route('book-appointment.getSlots', $doctor->id) }}`
        const handleSlot = (date, day, _chamber_id) => {
            // slot_grid.innerHTML = ''
            axios.post(slot_link, {
                chamber_id: _chamber_id,
                date,
                day
            }).then(res => {
                renderSlots(res.data || [])
            })
        }

        const store_link = `{{ route('book-appointment.store', $doctor->id) }}`

        const storeAppointment = () => {
            let data = {
                age,
                phone,
                patient_name,
                patient_type_id,
            }
            console.log(data);
        }
    </script>
@endsection
