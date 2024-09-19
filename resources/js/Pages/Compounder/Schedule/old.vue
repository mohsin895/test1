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
            <div class="flex justify-center flex-wrap gap-2 gap-y-7">
                <div
                    v-for="(item, index) in slot.appointments"
                    :key="index"
                    class="flex items-center gap-2"
                >
                    <div
                        @click="handleSerial(item)"
                        class="border w-14 h-14 grid place-items-center rounded-full text-center shadow-md shadow-slate-600/30 hover:bg-slate-200 select-none cursor-pointer"
                        :class="{
                            'border-green-600 text-green-600':
                                getStatus(slot.visitation_tracks, item) ==
                                'present',
                            'border-red-500 text-red-600':
                                getStatus(slot.visitation_tracks, item) ==
                                'absent',
                            'border-primary text-primary':
                                getStatus(slot.visitation_tracks, item) ==
                                'skip',
                            '!w-20 !h-20 border-slate-500':
                                getStatus(slot.visitation_tracks, item) ==
                                'current',
                        }"
                    >
                        <div class="text-2xl font-bold">
                            {{ item.serial_no }}
                        </div>
                    </div>
                    <div
                        v-if="index + 1 < slot.appointments.length"
                        class="text-slate-500"
                    >
                        <Icon name="PhArrowRight" size="26" />
                    </div>
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
import { ref } from "vue";
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
</script>
