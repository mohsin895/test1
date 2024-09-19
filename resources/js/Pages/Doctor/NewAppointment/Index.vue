<template>
    <DoctorLayout title="New Appointments">
        <div class="container mx-auto py-8">
                <form
                    @submit.prevent="handleBookAppointment"
                    class="mb-4 rounded bg-white"
                    
                >
                    <div
                       
                    >
                        <div class="grid md:grid-cols-2 gap-5">
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
                                <div >
                                    <label
                                        class="mb-2 block font-bold text-gray-900"
                                    >
                                        Patient Type:
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
                            <div class="md:flex mb-4 md:mb-0 items-center space-x-2">
                                <div
                                    class="flex items-center mb-4 md:mb-0"
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
                                            :value="doctorChamber.chamber_id"
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
                                class="grid md:grid-cols-3 gap-4 text-center text-sm md:text-base"
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
                                            class="bg-white block font-bold shadow py-2 px-2 rounded border-[#39c9ba] peer-checked:bg-[#39c9ba] peer-disabled:bg-red-100 border peer-disabled:cursor-not-allowed peer-disabled:border-red-500 peer-checked:text-white select-none truncate"
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
                            <div class="grid md:grid-cols-3 gap-4 text-center text-sm md:text-base">
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
    </DoctorLayout>
</template>

<script setup>
import DoctorLayout from "@/Layouts/DoctorLayout.vue";
import { Table, Button, Modal, Icon } from "@/plugins/ui";
import { Input, Textarea, Select } from "@/plugins/form";
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { isEmpty, get } from "lodash";
import Helper, { convert24to12 } from "@/helper";
import useNewAppointment from './useNewAppointment'

const {
    form,
    handleBookAppointment,
    getDates,
    getDoctorDates,
    getDoctorSlots,
    getSlots,
} = useNewAppointment();

const props = defineProps({
    
    doctor: {
        type: Object,
        default: {},
    },
    patientTypes: {
        type: Array,
        default: []
    },
   
});


const selectedDoctorChamber = ref();
const selectDoctorChamber = (doctorChamber) => {
    selectedDoctorChamber.value = doctorChamber;
};




</script>
