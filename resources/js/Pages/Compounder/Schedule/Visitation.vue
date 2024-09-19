<template>
    <CompounderLayout title="Visitation" aside="compounder">
        <div class="text-center mb-5">
            <span class="font-semibold capitalize">Day: </span>
            <span class="capitalize">{{ slot.day }}</span>
            <br />
            <span class="font-semibold capitalize">Chamber:</span>
            <span>{{ get(slot, "doctor_chamber.chamber.name") }}</span>
        </div>
        <h2 class="font-bold text-xl text-center">
            <div class="capitalize">
                Slot: {{ convert24to12(slot.from_time) }} -
                {{ convert24to12(slot.to_time) }}
            </div>
        </h2>

        <div
            v-if="!slot.visitation_tracks && slot.appointments.length"
            class="flex justify-center mt-5"
        >
            <Button.Primary @click="handleStart(slot)" outline>
                Start Visitation Now
            </Button.Primary>
        </div>

        <div
            v-if="slot.visitation_tracks && slot.appointments.length"
            class="py-2 mt-5"
        >
            <div class="flex justify-center mb-5">
                <div
                    class="text-xl font-bold border-b-[1.5px] border-dashed border-slate-400 pb-1 px-5"
                >
                    Serial Ongoing
                </div>
            </div>
            <div class="flex flex-col items-center gap-2 justify-center mb-6">
                <div
                    class="py-3 px-8 text-2xl border border-slate-400 text-slate-600 font-bold rounded-md shadow"
                >
                    {{ slot.visitation_tracks.current_serial || "Started" }}
                </div>
                <div v-if="slot.visitation_tracks.break_duration">
                    For
                    <span class="font-bold text-xl text-red-500">
                        {{ slot.visitation_tracks.break_duration }}
                    </span>
                    Minutes
                </div>
                <div v-if="slot.visitation_tracks?.end_at">
                    The {{ slot.visitation_tracks.current_serial }} time is
                    supposed to end at
                    <span class="font-bold text-xl text-red-500">
                        {{ slot.visitation_tracks.end_at }}
                    </span>
                </div>
            </div>

            <div class="flex justify-center mb-6">
                <div
                    class="text-xl font-bold border-b-[1.5px] border-dashed border-slate-400 pb-1 px-5"
                >
                    Manage Serial
                </div>
            </div>
            <div class="relative overflow-x-auto mt-4">
            
            <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
                <div>
                    <!-- <h3 class="text-lg font-semibold text-slate-800">Generated Invoice for this Month</h3>
                    <p class="text-slate-500">Overview of the invoices.</p> -->
                </div>
                <div class="ml-3">
                    <div class="w-full max-w-sm min-w-[200px] relative">
                    <div class="relative">
                        <input types="text"
                        v-model="search"
                        class="bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                        placeholder="Search for patient..."
                        />
                        <button
                        class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded "
                        type="button"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        </button>
                    </div>
                    </div>
                </div>
            </div>
             
            <div class="relative flex flex-col w-full h-full  text-gray-700 bg-white shadow-md  bg-clip-border">
              <table class="w-full text-left table-auto min-w-max border border-gray-400">
                <thead>
                  <tr class="bg-primary whitespace-nowrap text-sm ">
                    <th class="p-3 border-b border-slate-300 bg-slate-50">
                      <p class="block text-sm font-normal leading-none text-slate-500">
                        Sl.
                      </p>
                    </th>
                    <th class="p-3 border-b border-slate-300 bg-slate-50">
                      <p class="block text-sm font-normal leading-none text-slate-500">
                       Name
                      </p>
                    </th>
                    <th class="p-3 border-b border-slate-300 bg-slate-50" v-if="slot.visitation_tracks.current_serial =='Stop'">
                      <p class="block text-sm font-normal leading-none text-slate-500">
                     Status
                      </p>
                    </th>
                    <th class="p-3 border-b border-slate-300 bg-slate-50" v-else>
                      <p class="block text-sm font-normal leading-none text-slate-500">
                     Action
                      </p>
                    </th>
                    <th class="p-3 border-b border-slate-300 bg-slate-50">
                      <p class="block text-center text-sm font-normal leading-none text-slate-500">
                      Details
                      </p>
                    </th>
                    
                  </tr>
                </thead>
                <tbody>
                  <tr   v-for="(item, index) in filteredAppointments"
                  :key="index" :class="{ 'bg-red-500 hover:bg-red-400 text-white ': (item.is_running == 1), 'bg-rose-500 hover:bg-red-400 text-white': (item.is_next == 1) }" >
                    <td class="p-2 border-b border-slate-200 py-1">
                        <th
                    scope="row"
                    @click="handleSerial(item)"
                    class="border w-10 h-10 grid place-items-center rounded-full text-center justify-center shadow-md shadow-slate-600/30 hover:bg-slate-200 select-none"
                    :class="{
                         'border-black text-black': getStatus(report.visitation_tracks, item) == 'serialized',
                        'border-green-600 text-green-600': getStatus(slot.visitation_tracks, item) == 'present',
                        'border-green-600 text-green-600': getStatus(slot.visitation_tracks, item) == 'seen',
                        'border-primary text-primary': getStatus(slot.visitation_tracks, item) == 'skip',
                        'border-red-500 text-red-600':
                                            getStatus(slot.visitation_tracks, item) ==
                                            'absent',
                       
                    }"
                >
                   <span>
                    <span v-if="item.status =='old'">R</span>
                    <span v-else-if="item.status =='new'">R-{{item.serial_no}}</span>
                    <span v-else>  {{ item.serial_no }}</span>
                  </span>
                </th>
                    </td>
                    <td class="p-2 border-b border-slate-200 py-1" :class="{  ' text-white': (item.present == 4) }">
                        {{item.patient_name}}
                    </td>
                    
                    <td class="p-2 border-b border-slate-200 py-1" >
                        <span v-if="slot.visitation_tracks.current_serial =='Stop'">
                            <span class=" left-0 top-3 text-green-700 font-semibold" v-if="item.present == 7">Present</span>
                            <span class=" left-0 top-3  text-red-700 font-semibold" v-else>Absent</span>
                        </span>
                        <span v-else-if="slot.visitation_tracks.update_status =='Edit'">
                            <div class="flex gap-6 md:flex-nowrap flex-wrap rounded" style="font-weight: 400;">
                          <div class="w-full text-black">
                            <span class="absolute left-0 top-3 opacity-40">{{ placeholder }}</span>
                            <select
                              id="countries"
                              v-model="item.selectedType" 
                              @change="handleStatus(item)"
                              class="myInput border border-none focus:outline-none px-0 py-2 block w-full remove-shadow"
                            >
                            
                            <option  value="serialized">Serialized</option>
                              <option  value="present">Present</option>
                              <option  value="absent">Absent</option>
                              <option  value="running">Running</option>
                              <option  value="skip">Skip</option>
                             <option  value="next">Next</option>
                             <option disabled value="seen"> Present & Seen </option>
                             <option  value="report">Report</option>
                             
                            </select>
                            <span class="customBorder"></span>
                          </div>
                        </div>
                            </span>
                        <span v-else-if="item.present== 7">
                            <div class="flex gap-6 md:flex-nowrap flex-wrap rounded" style="font-weight: 400;">
                          <div class="w-full text-black">
                            <span class="absolute left-0 top-3 opacity-40">{{ placeholder }}</span>
                            <select
                              id="countries"
                              v-model="item.selectedType" 
                              @change="handleStatus(item)"
                              class="myInput border border-none focus:outline-none px-0 py-2 block w-full remove-shadow"
                            >
                            
                            <option  value="serialized">Serialized</option>
                              <option  value="present">Present</option>
                              <option  value="running">Running</option>
                              <option  value="skip">Skip</option>
                             <option  value="next">Next</option>
                             <option disabled value="seen"> Present & Seen </option>
                             <option  value="report">Report</option>
                             
                            </select>
                            <span class="customBorder"></span>
                          </div>
                        </div>
                        </span>
                        <span v-else>
                            <div class="flex gap-6 md:flex-nowrap flex-wrap rounded" style="font-weight: 400;">
                          <div class="w-full text-black">
                            <span class="absolute left-0 top-3 opacity-40">{{ placeholder }}</span>
                            <select
                              id="countries"
                              v-model="item.selectedType" 
                              @change="handleStatus(item)"
                              class="myInput border border-none focus:outline-none px-0 py-2 block w-full remove-shadow"
                            >
                            
                            <option  value="serialized">Serialized</option>
                              <option  value="present">Present</option>
                              <option  value="running">Running</option>
                              <option  value="skip">Skip</option>
                             <option  value="next">Next</option>
                             <option  value="report">Report</option>
                            </select>
                            <span class="customBorder"></span>
                          </div>
                        </div>
                        </span>
                        
                    </td>
                    <td class="p-2 border-b border-slate-200 py-1 ">
                        <div class="flex gap-2 items-center justify-center w-full">
                            <Button.Info @click="handleSerial(item)" class="!px-1 ">
                                    <Icon name="PhEye" size="16" weight="bold" /><span class="text-sm">View</span>
                                      </Button.Info>
                                      <span v-if="item.serialized_status.length > 0">
                                        <Button.Info @click="handleHistory(item)" class="!px-1 ">
                            <Icon name="PhInfo" size="16" weight="bold" />
                                </Button.Info>
                                      </span>
                                      <span v-else>
                                         <div class="w-[26px] h-[26px]">
            
                                         </div>
                                      </span>
                              
                           </div>
                    </td>
                  
                  </tr>
                  
                </tbody>
              </table>
            
            </div>
                         </div>

            <div>
                <div class="flex justify-center mb-5 mt-6">
                    <div
                        class="text-xl font-bold border-b-[1.5px] border-dashed border-slate-400 pb-1 px-5"
                    >
                        More Options
                    </div>
                </div>
                <div class="flex justify-center gap-3 mt-6">
                    <Button.Primary
                        :outline="
                            !(
                                get(slot, 'visitation_tracks.current_serial') ==
                                'Emergency'
                            )
                        "
                        @click="
                            openBreakPopup(
                                'Emergency',
                                slot.visitation_tracks?.break_duration
                            )
                        "
                    >
                        Emergency
                    </Button.Primary>
                    <Button.Primary
                        :outline="
                            !(
                                get(slot, 'visitation_tracks.current_serial') ==
                                'Break'
                            )
                        "
                        @click="
                            openBreakPopup(
                                'Break',
                                slot.visitation_tracks?.break_duration
                            )
                        "
                    >
                        Break
                    </Button.Primary>
                    <Button.Primary
                        :outline="
                            !(
                                get(slot, 'visitation_tracks.current_serial') ==
                                'Prayer'
                            )
                        "
                        @click="
                            openBreakPopup(
                                'Prayer',
                                slot.visitation_tracks?.break_duration
                            )
                        "
                    >
                        Prayer
                    </Button.Primary>
                </div>
            </div>
        </div>
        <div v-else class="text-center py-5 text-slate-500 mt-10 text-xl">
            <template v-if="!slot.appointments.length">
                No serial found
            </template>
        </div>

        <Modal v-model="selectedSerial">
            <div
                v-if="selectedSerial"
                class="w-[500px] bg-white py-4 px-5 rounded-md"
            >
                <div class="font-bold border-b pb-2 border-slate-300">
                    Appointment information
                </div>
                <div class="py-4 grid gap-2">
                    <div>
                        <span class="font-bold">Name:</span>
                        {{ selectedSerial.patient_name }}
                    </div>
                    <div>
                        <span class="font-bold">Age:</span>
                        {{ selectedSerial.age }}
                    </div>
                    <div>
                        <span class="font-bold">Phone:</span>
                        {{ selectedSerial.phone }}
                    </div>
                    <div>
                        <span class="font-bold">Type:</span>
                        <span
                            class="ml-2 py-0.5 px-3 rounded-xl bg-blue-100 border border-blue-500"
                            >{{
                                get(selectedSerial, "patient_type.name")
                            }}</span
                        >
                    </div>
                    <div>
                        <span class="font-bold">Tracking Code:</span>
                        {{ get(selectedSerial, "tracking_code") }}
                    </div>
                    <div class="flex flex-wrap justify-center gap-4 mt-5">
                        <div v-if="selectedSerial.present === 1">
                            <Button.Success
                                @click="handleUploadModal(selectedSerial)"
                                class="bg-slate-800 border-slate-800"
                            >
                                Upload Prescription
                            </Button.Success>
                        </div>
                        <div v-else>
                            <Button.Success
                                @click="handleStatus(selectedSerial, 'present')"
                            >
                                Present
                            </Button.Success>
                        </div>

                        <Button.Primary
                            @click="handleStatus(selectedSerial, 'skip')"
                            class="bg-primary border-primary"
                        >
                            Skip
                        </Button.Primary>
                        <Button.Danger
                            @click="handleStatus(selectedSerial, 'absent')"
                        >
                            Absent
                        </Button.Danger>
                    </div>
                </div>
            </div>
        </Modal>

        <Modal v-model="breakModal">
            <div
                v-if="breakModal"
                class="md:w-[500px] bg-white py-4 px-5 rounded-md select-none"
            >
                <div class="font-bold border-b pb-2 border-slate-300">
                    <span v-if="breakModal == 'Emergency'">
                        Do you want to have some time for emergency issues?
                    </span>
                    <span v-if="breakModal == 'Prayer'">
                        Do you want to have some time for prayer?
                    </span>
                    <span v-if="breakModal == 'Break'">
                        Do you want to have some time for break?
                    </span>
                </div>
                <div class="py-3">
                    <div
                        class="h-[90px] flex justify-center items-center gap-2 mt-12"
                    >
                        <div class="w-[100px]"></div>
                        <TimeSlider v-model="handleBreakForm.minute" />
                        <div class="w-[100px]">Minutes</div>
                    </div>
                    <div class="flex justify-center mt-16">
                        <Button.Primary
                            @click="handleBreak(slot.visitation_tracks)"
                        >
                            Save
                        </Button.Primary>
                    </div>
                </div>
            </div>
        </Modal>

        <Modal v-model="prescriptionModal">
            <div v-if="selectedSerial">
                <form
                    @submit.prevent="handleUpload"
                    class="bg-white py-8 px-5 w-[400px] md:min-w-[600px]"
                >
                    <Input.File
                        type="file"
                        label="Prescription"
                        @input="(e) => (uploadForm.media = e.target.files[0])"
                        :error="uploadForm.errors.media"
                    />
                    <div class="flex justify-end">
                        <Button.Primary
                            type="submit"
                            :loading="uploadForm.processing"
                            class="mt-4"
                        >
                            Upload
                        </Button.Primary>
                    </div>
                </form>
            </div>
        </Modal>
    </CompounderLayout>
</template>

<script setup>
import CompounderLayout from "@/Layouts/CompounderLayout.vue";
import { get } from "lodash";
import { Input } from "@/plugins/form";
import { Icon, Modal, Button } from "@/plugins/ui";
import Helper, { convert24to12, notify } from "@/helper";
import { useForm, Link } from "@inertiajs/vue3";
import { ref, computed } from 'vue';
import TimeSlider from "./fragments/TimeSlider.vue";

const props = defineProps({
    slot: {
        type: Object,
        default: {},
    },
    date: String,
});

const form = useForm({
    day: null,
    date: null,
    slot_id: null,
    doctor_chamber_id: null,
    note: null,
});

const uploadForm = useForm({
    media: null,
});

const appointment_status_form = useForm({
    appointment_id: null,
    status: null,
});

const handleBreakForm = useForm({
    note: null,
    type: "Emergency",
    track_id: null,
    minute: 1,
});

const search = ref("");
const selectedSerial = ref(false);
const breakModal = ref(false);
const prescriptionModal = ref(false);

const handleSerial = (serial) => {
    selectedSerial.value = serial;
};
const openBreakPopup = (type, duration) => {
    breakModal.value = type;
    handleBreakForm.minute = +duration || 1;
};

const handleBreak = (track) => {
    if (!track?.id) return;
    handleBreakForm.track_id = track.id;
    handleBreakForm.type = breakModal.value;

    handleBreakForm.post(route("compounder.schedule.save_break"), {
        onFinish() {
            handleBreakForm.reset();
            breakModal.value = false;
        },
    });
};

const getStatus = (track, appointment) => {
    let status = "";
   
        if (appointment.present == 0) {
            status = "serialized";
        }
        if (appointment.present == 1) {
            status = "present";
        }
        if (appointment.present == 2) {
            status = "absent";
        }
        if (appointment.present == 3) {
            status = "skip";
        }
        if (appointment.present == 4) {
            status = "next";
        }
        if (appointment.present == 5) {
            status = "report";
        }
        if (appointment.present == 7) {
            status = "seen";
        }
    
    return status;
};

const handleStatus = (appointment, type) => {
    Helper.confirm(`Are you sure to mark as ${type}?`, () => {
        appointment_status_form.appointment_id = appointment.id;
        appointment_status_form.status = type;
        appointment_status_form.post(route("compounder.schedule.update_status"), {
            onFinish() {
                appointment_status_form.reset();
                selectedSerial.value = false;
            },
        });
    });
};

const handleStart = (slot) => {
    Helper.confirm("Are you sure to start visitation?", () => {
        form.day = slot.day;
        form.date = props.date;
        form.slot_id = slot.id;
        form.doctor_chamber_id = slot.doctor_chamber_id;
        form.post(route("compounder.schedule.start", slot.id), {
            onFinish() {
                form.reset();
            },
        });
    });
};

const handleUploadModal = (selectedSerial) => {
    form.id = selectedSerial.id;
    form.media = selectedSerial.media;
    prescriptionModal.value = true;
};
const handleUpload = () => {
    uploadForm.post(route("compounder.schedule.prescription-upload", form.id), {
        onFinish() {
            uploadForm.reset();
            prescriptionModal.value = false;
        },
    });
};

const filteredAppointments = computed(() => {
    if (!search.value) {
        return props.slot.appointments;
    }
    return props.slot.appointments.filter(appointment => 
    appointment.patient_name.toLowerCase().includes(search.value.toLowerCase()) ||
    appointment.serial_no.toString().toLowerCase().includes(search.value.toLowerCase()) ||
    appointment.present.toString().toLowerCase().includes(search.value.toLowerCase())
    );
});

const filteredAppointmentsReports = computed(() => {
    if (!searchReport.value) {
        return props.report.appointments;
    }
    return props.report.appointments.filter(appointment => 
    appointment.patient_name.toLowerCase().includes(searchReport.value.toLowerCase()) ||
    appointment.serial_no.toString().toLowerCase().includes(searchReport.value.toLowerCase()) ||
    appointment.present.toString().toLowerCase().includes(searchReport.value.toLowerCase())
    );
});
</script>
