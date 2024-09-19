<template>
    <Auth title="Book Appointment">
        <div class="mt-8">
            <h2 class="text-gray-800 mb-4 flex justify-between items-center">
                <div class="text-xl font-semibold">Doctors</div>
                <div>
                    <div class="flex justify-end mt-2">
                        <Input.Primary
                            type="search"
                            placeholder="search here..."
                            v-model="search"
                            @input="getDoctors()"
                        />
                    </div>
                </div>
            </h2>
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
            >
                <div v-if="!filteredDoctors.data?.length">
                    <span class="text-red-500">No match found..</span>
                </div>
                <label
                    v-for="(doctor, index) in filteredDoctors.data"
                    :key="index"
                    class="relative"
                >
                    <!-- {{ doctor.id }} -->
                    <input
                        type="radio"
                        v-model="form.doctor_id"
                        :value="doctor.id"
                        class="hidden peer"
                    />
                    <div
                        class="border p-2 rounded relative shadow-md cursor-pointer hover:bg-slate-50 peer-checked:border-primary hover:border-primary"
                        @click="selectDoctor(doctor)"
                    >
                        <span
                            v-if="form.doctor_id == doctor.id"
                            class="block w-5 h-5 rounded-full bg-primary absolute top-2 left-2"
                        ></span>
                        <div class="flex justify-center my-2">
                            <div
                                class="w-14 h-14 rounded-full overflow-hidden bg-slate-200 border border-slate-300"
                            >
                                <Img 
                                    :src="get(doctor, 'media.path')" 
                                    fallBack="/frontend/avatar.jpeg"
                                    class="w-full h-full rounded-full object-cover object-center"
                                />
                            </div>
                        </div>
                        <h3 class="text-center font-semibold mt-2">
                            {{ doctor.name }}
                        </h3>
                        <div class="flex flex-wrap gap-1 justify-center">
                            <div
                                v-for="(
                                    doctorSpecialization, index
                                ) in doctor.specializations"
                                :key="index"
                            >
                                <span>{{
                                    get(
                                        doctorSpecialization,
                                        "specialization.name"
                                    )
                                }}</span>
                                <span
                                    v-if="
                                        index <
                                        doctor.specializations?.length - 1
                                    "
                                    >,
                                </span>
                            </div>
                        </div>
                    </div>
                </label>
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
                                Select a Chamber :
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
                                            v-model="form.chamber_id"
                                            :value="doctorChamber.id"
                                            @click="
                                                selectDoctorChamber(
                                                    doctorChamber
                                                )
                                            "
                                            @change="getDates(doctorChamber)"
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

                                <span
                                    class="col-span-4 text-start"
                                    v-if="!selectedDoctorChamber.slots?.length"
                                    >No dates available for the selected
                                    chamber.</span
                                >
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
    </Auth>
</template>

<script setup>
import Auth from "@/Layouts/Auth.vue";
import { Button, Icon, Img } from "@/plugins/ui";
import { Input } from "@/plugins/form";
import { ref, onMounted } from "vue";
import { get } from "lodash";
import { useBookAppointment } from "@/Pages/Admin/BookAppointment/useBookAppointment";
import Helper, { convert24to12 } from "@/helper";

const {
    form,
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
});

const selectedDoctor = ref();
const selectDoctor = (doctor) => {
    selectedDoctor.value = doctor;
    selectedDoctorChamber.value = null;
    getDoctorSlots.value = null;
};
const selectedDoctorChamber = ref();
const selectDoctorChamber = (doctorChamber) => {
    selectedDoctorChamber.value = doctorChamber;
};
// const selectedSlot = ref();
// const getSlots = (doctorDate) => {
//     selectedSlot.value = doctorDate;
//     console.log(selectedSlot.value);
// };

onMounted(() => {
    getDoctors();
});
</script>
