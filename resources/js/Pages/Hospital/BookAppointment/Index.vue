<template>
    <HospitalLayout title="Book Appointment">
        <div class="mt-4">
            <h2 class="text-gray-800 mb-4 flex justify-between items-center">
                <div class="text-xl font-semibold">Designations</div>
            </h2>
            <div class="flex flex-wrap items-center">
                <label
                    class="mr-4"
                    v-for="(designation, index) in designations"
                    :key="index"
                >
                    <input
                        type="checkbox"
                        v-model="sorting.designation_ids"
                        name="designation_ids[]"
                        :value="designation.id"
                        class="mr-2"
                        @change="getDoctors()"
                    />
                    {{ designation.name }}
                </label>
            </div>
        </div>
        <div class="mt-4">
            <h2 class="text-gray-800 mb-2 flex justify-between items-center">
                <div class="text-xl font-semibold">Specializations</div>
            </h2>
            <div class="flex flex-wrap items-center">
                <label
                    class="mr-4"
                    v-for="(specialization, index) in specializations"
                    :key="index"
                >
                    <input
                        type="checkbox"
                        v-model="sorting.specialization_ids"
                        name="specialization_ids[]"
                        :value="specialization.id"
                        class="mr-2"
                        @change="getDoctors()"
                    />
                    {{ specialization.name }}
                </label>
            </div>
        </div>
        <div class="mt-4">
            <h2 class="text-gray-800 mb-2 flex justify-between items-center">
                <div class="text-xl font-semibold">Degrees</div>
            </h2>
            <div class="flex flex-wrap items-center">
                <label
                    class="mr-4"
                    v-for="(degree, index) in degrees"
                    :key="index"
                >
                    <input
                        type="checkbox"
                        v-model="sorting.degree_ids"
                        name="degree_ids[]"
                        :value="degree.id"
                        class="mr-2"
                        @change="getDoctors()"
                    />
                    {{ degree.name }}
                </label>
            </div>
        </div>
        <div class="mt-8">
            <h2 class="text-gray-800 mb-4 flex justify-between items-center">
                <div class="text-xl font-semibold">Doctors</div>
                <div>
                    <div class="flex justify-end mt-2">
                        <Input.Primary
                            type="search"
                            placeholder="Search doctors"
                            v-model="search"
                            @input="getDoctors()"
                        />
                    </div>
                </div>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <!-- No Doctors Found -->
                <div v-if="!filteredDoctors.data?.length">
                    <span class="text-red-500">No match found!</span>
                </div>

                <!-- Doctor Card -->
                <div
                    v-for="(doctor, index) in filteredDoctors.data"
                    :key="index"
                    class="relative cursor-pointer"
                    @click="selectDoctor(doctor)"
                >
                    <div
                        class="border p-2 rounded relative shadow-md hover:bg-slate-50 transition-colors duration-300"
                        :class="{
                            'border-primary bg-slate-100': form.doctor_id === doctor.id
                        }"
                    >
                        <!-- Highlight Indicator -->
                        <span
                            v-if="form.doctor_id === doctor.id"
                            class="block w-5 h-5 rounded-full bg-primary absolute top-2 left-2"
                        ></span>

                        <!-- Doctor Image -->
                        <div class="flex justify-center my-2">
                            <div class="w-14 h-14 rounded-full overflow-hidden bg-slate-200 border border-slate-300">
                                <Img 
                                    :src="get(doctor, 'media.path')" 
                                    fallBack="/frontend/avatar.jpeg"
                                    class="w-full h-full object-cover object-center"
                                />
                            </div>
                        </div>

                        <!-- Doctor Name -->
                        <h3 class="text-center font-semibold mt-2">
                            {{ doctor.name }}
                        </h3>

                        <!-- Specializations -->
                        <div class="flex flex-wrap gap-1 justify-center">
                            <span
                                v-for="(spec, idx) in doctor.specializations"
                                :key="idx"
                            >
                                {{ get(spec, 'specialization.name') }}<span v-if="idx < doctor.specializations.length - 1">,</span>
                            </span>
                        </div>

                        <!-- Weekdays -->
                        <div class="flex flex-wrap gap-1 justify-center mt-1">
                            <span
                                v-for="(day, idx) in doctor.doctor_chambers[0].weekdays"
                                :key="idx"
                                class="font-semibold"
                            >
                                {{ formatWeekday(day) }}<span v-if="idx < doctor.doctor_chambers[0].weekdays.length - 1">,</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <div
                class="flex items-center justify-center my-9"
                v-if="filteredDoctors.data?.length"
            >
                <div
                    v-for="(link, index) in filteredDoctors.links"
                    :key="index"
                >
                    <button
                        v-if="link.url"
                        :class="[
                            'px-3 py-2',
                            link.active
                                ? 'text-white bg-indigo-600'
                                : 'text-gray-500 hover:text-blue-500',
                        ]"
                        @click="getDoctors(link.url)"
                    >
                        <span v-html="link.label"></span>
                    </button>
                    <span
                        v-else
                        class="px-3 py-2 text-gray-500 hover:text-blue-500"
                        v-html="link.label"
                    ></span>
                </div>
            </div>
            <!-- Form start -->
            <div class="container mx-auto py-8">
                <form
                    @submit.prevent="handleBookAppointment"
                    class="mb-4 rounded bg-white"
                    v-for="(doctor, index) in filteredDoctors.data"
                    :key="index"
                >
                    <div
                        v-if="selectedDoctor && selectedDoctor.id === doctor.id"
                    >
                        <div class="grid grid-cols-2 gap-5">
                            <div class="mb-4">
                                <label
                                    class="mb-2 block font-bold text-gray-900"
                                >
                                    Patient Name:
                                </label>
                                <Input.Primary
                                    v-model="form.patient_name"
                                    :error="form.errors.patient_name"
                                    placeholder="Enter patient name"
                                />
                            </div>
                            <div class="mb-4">
                                <label
                                    class="mb-2 block font-bold text-gray-900"
                                >
                                    Phone Number:
                                </label>
                                <Input.Primary
                                    type="text"
                                    v-model="form.phone"
                                    :error="form.errors.phone"
                                    placeholder="Enter phone number"
                                />
                            </div>
                            <div class="mb-4">
                                <label
                                    class="mb-2 block font-bold text-gray-900"
                                >
                                     Patient age:
                                </label>
                                <Input.Primary
                                    v-model="form.age"
                                    :error="form.errors.age"
                                    placeholder="Enter patient age"
                                />
                            </div>
                            <div>
                                <div>
                                    <label
                                        class="mb-2 block font-bold text-gray-900"
                                    >
                                        Patient Type :
                                    </label>
                                    <div
                                        v-for="(
                                            patientType, index
                                        ) in patientTypes"
                                        :key="index"
                                        class="flex"
                                    >
                                        <div
                                            v-if="
                                                patientType.name ===
                                                'New Patient'
                                            "
                                        >
                                            <div class="flex items-center">
                                                <label>
                                                    <input
                                                        class="mr-2 leading-tight"
                                                        type="radio"
                                                        v-model="
                                                            form.patient_type_id
                                                        "
                                                        :value="patientType.id"
                                                    />

                                                    {{ patientType.name }}
                                                </label>
                                                <span
                                                    class="ml-1"
                                                    v-if="
                                                        doctor.doctor_details
                                                            ?.new_fees
                                                    "
                                                >
                                                    ({{
                                                        doctor.doctor_details
                                                            ?.new_fees
                                                    }}) BDT
                                                </span>
                                            </div>
                                        </div>
                                        <div
                                            v-if="
                                                patientType.name ===
                                                'Old Patient'
                                            "
                                        >
                                            <div class="flex items-center">
                                                <label>
                                                    <input
                                                        class="mr-2 leading-tight"
                                                        type="radio"
                                                        v-model="
                                                            form.patient_type_id
                                                        "
                                                        :value="patientType.id"
                                                    />

                                                    {{ patientType.name }}
                                                </label>
                                                <span
                                                    class="ml-1"
                                                    v-if="
                                                        doctor.doctor_details
                                                            ?.old_fees
                                                    "
                                                >
                                                    ({{
                                                        doctor.doctor_details
                                                            ?.old_fees
                                                    }}) BDT</span
                                                >
                                            </div>
                                        </div>
                                        <div
                                            v-if="
                                                patientType.name ===
                                                'Report Patient'
                                            "
                                        >
                                            <div class="flex items-center">
                                                <label>
                                                    <input
                                                        class="mr-2 leading-tight"
                                                        type="radio"
                                                        v-model="
                                                            form.patient_type_id
                                                        "
                                                        :value="patientType.id"
                                                    />

                                                    {{ patientType.name }}
                                                </label>
                                                <span
                                                    class="ml-1"
                                                    v-if="
                                                        doctor.doctor_details
                                                            ?.report_fees
                                                    "
                                                >
                                                    ({{
                                                        doctor.doctor_details
                                                            ?.report_fees
                                                    }}) BDT</span
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="form.errors.patient_type_id" class="text-red-500 text-xs">
                                        The patient type field is required.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="mb-2 block font-bold text-gray-900">
                                Selected Chamber :
                            </label>
                            <div class="flex items-center space-x-2">
                                <div
                                    class="flex items-center"
                                    v-for="(
                                        doctorChamber, index
                                    ) in doctor.doctor_chambers"
                                    :key="index"
                                >
                                    <label>
                                        <input
                                            class="mr-2 leading-tight"
                                            type="radio"
                                            checked
                                            v-model="form.chamber_id"
                                            :value="doctorChamber.id"
                                           
                                           
                                        />
                                        {{ get(doctorChamber, "chamber.name") }}
                                    </label>
                                </div>
                                <div v-if="!doctor.doctor_chambers?.length">
                                    <span
                                        >No chambers available for this
                                        doctor</span
                                    >
                                </div>
                            </div>
                            <div v-if="form.errors.chamber_id" class="text-red-500 text-xs">
                                The chamber field is required.
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="mb-2 block font-bold text-gray-900">
                                Doctor Present Date :
                            </label>
                            <div
                                v-if="selectedDoctorChamber"
                                class="grid grid-cols-3 gap-4 text-center"
                            >
                                <div
                                    v-for="(
                                        doctorDate, index
                                    ) in getDoctorDates"
                                    :key="index"
                                >
                                    <label
                                        class="block cursor-pointer relative"
                                    >
                                        <span v-if="doctorDate.full_off_day" class="absolute text-[10px] bg-red-500 text-white py-0.5 px-2 rounded-full -top-3 right-2">
                                            Off Day
                                        </span>
                                        <input
                                            type="radio"
                                            class="hidden peer"
                                            :value="doctorDate._date"
                                            v-model="form.date"
                                            :disabled="doctorDate.full_off_day"
                                            @change="
                                                getSlots(
                                                    selectedDoctorChamber,
                                                    doctorDate._date,
                                                    doctorDate.day
                                                )
                                            "
                                        />
                                        <span
                                            class="bg-white block font-bold shadow py-2 px-5 rounded border-[#39c9ba] peer-checked:bg-[#39c9ba] peer-disabled:bg-red-100 border peer-disabled:cursor-not-allowed peer-disabled:border-red-500 peer-checked:text-white select-none truncate"
                                        >
                                            {{ doctorDate.date }}
                                        </span>
                                    </label>
                                </div>

                               
                            </div>
                            <span v-if="!selectedDoctorChamber"
                                >Select a chamber first to see available
                                dates.</span
                            >
                        </div>
                        <div class="mb-4">
                            <label class="mb-2 block font-bold text-gray-900">
                                Available Slot(s) :
                            </label>
                            <div class="grid grid-cols-4 gap-4 text-center">
                                <div
                                    v-for="(
                                        doctorSlots, index
                                    ) in getDoctorSlots"
                                    :key="index"
                                    
                                >
                                    <label
                                        class="block cursor-pointer relative"
                                    >
                                        <input
                                            type="radio"
                                            hidden
                                            class="peer"
                                            :value="doctorSlots.id"
                                            v-model="form.slot_id"
                                        />
                                        <span
                                            class="bg-white block font-bold shadow py-2 px-5 rounded border-[#39c9ba] peer-checked:bg-[#39c9ba] peer-disabled:bg-red-100 border peer-disabled:cursor-not-allowed peer-disabled:border-red-500 peer-checked:text-white select-none truncate"
                                        >
                                            {{
                                                convert24to12(
                                                    doctorSlots.from_time
                                                )
                                            }}
                                            -
                                            {{
                                                convert24to12(
                                                    doctorSlots.to_time
                                                )
                                            }}
                                            <span class="ml-5"
                                                >0/
                                                {{ doctorSlots.limit }}</span
                                            >
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div v-if="!getDoctorSlots">
                                <span
                                    >Select a date first to see available
                                    slots.</span
                                >
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-end">
                            <Button.Primary type="submit" class="px-10 py-2" outline>
                                Book Now
                            </Button.Primary>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </HospitalLayout>
</template>

<script setup>
import HospitalLayout from "@/Layouts/HospitalLayout.vue";
import { Button, Icon, Img } from "@/plugins/ui";
import { Input } from "@/plugins/form";
import { ref, onMounted } from "vue";
import { get } from "lodash";
import { useBookAppointment } from "@/Pages/Hospital/BookAppointment/useBookAppointment";
import Helper, { convert24to12 } from "@/helper";

const {
    form,
    sorting,
    search,
    getDoctors,
    filteredDoctors,
    handleBookAppointment,
    getDates,
    getDoctorDates,
    getDoctorSlots,
    getSlots,
} = useBookAppointment();

defineProps({
    patientTypes: {
        type: Array,
        default: [],
    },
    designations: {
        type: Array,
        default: [],
    },
    specializations: {
        type: Array,
        default: [],
    },
    degrees: {
        type: Array,
        default: [],
    },
    chamber:{
        type:Object,
    }
});

const selectedDoctorChamber = ref();
const selectedDoctor = ref();

const formatWeekday = (weekday) => {
    return weekday.charAt(0).toUpperCase() + weekday.slice(1, 3).toLowerCase();
};


const selectDoctor = (doctor) => {
    if (form.doctor_id === doctor.id) {
       
        form.doctor_id = null;
        selectedDoctor.value = null;
        selectedDoctorChamber.value = null;
        getDoctorSlots.value = null;
    } else {

        form.doctor_id = doctor.id;
        selectedDoctor.value = doctor;
        selectedDoctorChamber.value = doctor; 
        getDoctorSlots.value = null;
        getDates(doctor); 
    }
};


// const selectDoctor = (doctor) => {
//     selectedDoctor.value = doctor;
//     selectedDoctorChamber.value = null;
//     getDoctorSlots.value = null;
//     selectedDoctorChamber.value = doctor;
//     selectedDoctorChamber.value = doctor;
// };

// const selectDoctorChamber = (doctorChamber) => {
//     selectedDoctorChamber.value = doctorChamber;
// };
// const selectedSlot = ref();
// const getSlots = (doctorDate) => {
//     selectedSlot.value = doctorDate;
//     console.log(selectedSlot.value);
// };

onMounted(() => {
    getDoctors();
});
</script>
