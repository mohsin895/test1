<template>
    <DoctorLayout title="Visitation">
        <div class="text-center items-center justify-center mb-5 md:flex gap-4">
            <!-- <span class="font-semibold capitalize">Day: </span> -->
            <span class="capitalize">{{ slot.day }}&nbsp;{{dateFormate( currentdate) }}</span>
            <br />
            <!-- <span class="font-semibold capitalize">Chamber:</span> -->
            <span>{{ get(slot, "doctor_chamber.chamber.name") }}</span>
            <span>
                <h2 class="font-bold text-xl text-center">
            <div class="capitalize">
              {{ convert24to12(slot.from_time) }} -
                {{ convert24to12(slot.to_time) }}
            </div>
        </h2>
            </span>
        </div>
        
        

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
                <div v-if="hasPreviousAppointments" class="flex justify-center mt-4">
                <Button.Primary @click="loadPrevious">Show All</Button.Primary>
            </div>
     
                <Table
           :config="tableConfig"
             :data="visibleAppointments"
           :trClass="trClass"
           :searchKeys="searchKeys"
           :theadClass="theadClass"
       >
       <template #serial="{ item }">
    <th
        scope="row"
        @click="handleSerial(item)"
        class="border w-6 h-6 grid place-items-center rounded-full text-center shadow-md shadow-slate-600/30 hover:bg-slate-200 select-none"
        :class="{
            'border-green-600 text-green-600': getStatus(slot.visitation_tracks, item) == 'present',
            'border-red-500 text-red-600': getStatus(slot.visitation_tracks, item) == 'absent',
            'border-primary text-primary': getStatus(slot.visitation_tracks, item) == 'skip',
            '!w-8 !h-8 border-slate-500': getStatus(slot.visitation_tracks, item) == 'current',
        }"
    >
        {{ item.serial_no }}
    </th>
</template>

           <template #name="{ item }">
            {{item.patient_name}}
           </template>
           <template #action="{ item }">
            <div class="flex gap-6 md:flex-nowrap flex-wrap rounded  mb-2" style="font-weight: 400;">
              <div class="w-full">
                <span class="absolute left-0 top-3 opacity-40">{{ placeholder }}</span>
                <select
                  id="countries"
                  v-model="item.selectedType" 
                  @change="handleStatus(item)"
                  class="myInput border border-none focus:outline-none px-0 py-3 block w-full remove-shadow"
                >
                  <option selected>Select Status</option>
                  <option :selected="item.present === 1" value="present">Present</option>
       
            <option :selected="item.present === 2" value="skip">Skip</option>
           
               <option :selected="item.present === 0" value="absent">Absent</option>
     
                </select>
                <span class="customBorder"></span>
              </div>
            </div>
          </template>
          
           <template #details="{ item }">
               <div class="flex gap-1  w-full">
                <Button.Info @click="handleSerial(item)" class="!px-1 ">
                        <Icon name="PhEye" size="16" weight="bold" /><span>View</span>
                          </Button.Info>
               </div>
           </template>
       </Table>
       <!-- <div v-if="hasMoreAppointments" class="flex justify-center mt-4">
    <Button.Primary @click="loadMore">Load More</Button.Primary>
</div> -->
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
    </DoctorLayout>
</template>


<script setup>
import DoctorLayout from "@/Layouts/DoctorLayout.vue";
import { get } from "lodash";
import { Input } from "@/plugins/form";
import { Table, Button, Modal, Icon } from "@/plugins/tableSchudle";
import Helper, { convert24to12, notify,dateFormate } from "@/helper";
import { useForm, Link } from "@inertiajs/vue3";
import { ref, computed } from 'vue';
import TimeSlider from "./fragments/TimeSlider.vue";
const dropdownVisible = ref({});
const today = new Date();
const currentdate = ref(today.toISOString().split('T')[0]);

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

const selectedSerial = ref(false);
const breakModal = ref(false);
const prescriptionModal = ref(false);
const type=ref();
const visibleStart = ref(0);  
const visibleCount = ref(2); 
const visibleCountData = ref(1); 

const visibleAppointments = computed(() => {
    return props.slot.appointments.slice(visibleStart.value, visibleStart.value + visibleCount.value);
});

const hasMoreAppointments = computed(() => {
    return visibleStart.value + visibleCount.value < props.slot.appointments.length;
});

const hasPreviousAppointments = computed(() => {
    return visibleStart.value > 0;
});


const loadMoreData = () => {
    if (hasMoreAppointments.value) {
        visibleStart.value += visibleCountData.value;
    }
};


const loadPreviousData = () => {
    if (hasPreviousAppointments.value) {
        visibleStart.value -= visibleCountData.value;
    }
}
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

    handleBreakForm.post(route("doctor.schedule.save_break"), {
        onFinish() {
            handleBreakForm.reset();
            breakModal.value = false;
        },
    });
};

const getStatus = (track, appointment) => {
    let status = "";
    if (track.current_serial == appointment.serial_no) {
        status = "current";
    } else {
        if (appointment.present == 0) {
            status = "absent";
        }
        if (appointment.present == 1) {
            status = "present";
        }
        if (appointment.present == 2) {
            status = "skip";
        }
    }
    return status;
};


const handleStatus = (item) => {
    let color = ''; 

    if (item.selectedType === 'present') {
        color = 'text-green-600';  
    } else if (item.selectedType === 'skip') {
        color = 'text-primary';  
    } else if (item.selectedType === 'absent') {
        color = 'text-red-600';  
    }

    const message = `Are you sure to mark as <span class='${color} font-bold' style='font-size: 1.65rem;'>${item.selectedType}</span> ?`;

    Helper.confirm(message, () => {
        appointment_status_form.appointment_id = item.id;
        appointment_status_form.status = item.selectedType;  
        appointment_status_form.post(route("doctor.schedule.update_status"), {
            onFinish() {
                appointment_status_form.reset();
                selectedSerial.value = false;
                loadMoreData();
              
             
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
        form.post(route("doctor.schedule.start", slot.id), {
            onFinish() {
                form.reset();
            },
        });
    });
};

const handleUploadModal = (selectedSerial) => {
  
    form.id = selectedSerial.id;
    uploadForm.media = null; 
    prescriptionModal.value = true; 
};
const handleUpload = () => {
    uploadForm.post(route("doctor.schedule.prescription-upload", form.id), {
        onFinish() {
            uploadForm.reset();
            prescriptionModal.value = false;
        },
    });
};

const tableConfig = [
   
    
];

const searchKeys = ["patient_name", "phone", "age", "date", "patient_type.name"];

const theadClass = "!bg-primary !text-white";
const trClass = "hover:bg-gray-100";
</script>
<style scoped>
.customBorder{
    display: block;
    border-bottom: 1px solid #0002;
    position: relative;
}
.customBorder::before{
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0px;
    border-bottom: 1px solid red;
    transition: 0.3s ease-in-out;
}

.myInput:focus + .customBorder::before{
    width: 100%;
}
.remove-shadow{
    box-shadow: none;
}

select option.font-semibold {
    font-weight: 600;
}

</style>