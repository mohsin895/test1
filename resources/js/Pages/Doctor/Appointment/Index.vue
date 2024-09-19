<template>
    <DoctorLayout title="Appointments">
        <div 
            v-if="activeSlot"
            class="mb-4 grid grid-cols-2 gap-4 border border-slate-200 rounded-md p-4 text-center"
        >
            <div class="text-center col-span-3">
                <div class="font-semibold">
                    <template v-if="activeTab=='today'">
                        {{ format(new Date(), "PPPP") }}
                    </template>
                    <template v-if="activeTab=='tomorrow'">
                        {{ format(new Date(props.tomorrow) ,'PPPP') }}
                    </template>
                </div>
            </div>
            <div>
                <label class="mb-2 block font-semibold text-gray-700">
                    Chamber
                </label>
                <span>
                    {{ activeChamber.name }}
                </span>
            </div>
            <div>
                <label class="mb-2 block font-semibold text-gray-700">
                    Slot
                </label> 
                {{ convert24to12(activeSlot.from_time) }} - {{ convert24to12(activeSlot.to_time) }}
            </div>
        </div>

        <div class="flex justify-between items-center flex-wrap gap-5 mb-5">
            <div>
                <Button.Primary
                    v-if="activeTab && !appointments.length"
                    outline
                    @click="handleBack(activeTab, activeSlot)"
                >
                    <Icon name="PhCaretLeft" />
                    Back
                </Button.Primary>
            </div>
            <div v-if="activeTab && !activeSlot" class="font-semibold capitalize">
                {{ activeTab }}
            </div>
        </div>

        <div class="mb-5 sm:flex justify-center items-center">
            <div v-if="!activeTab" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <div
                    v-for="(tab, index) in ['today', 'tomorrow', 'upcoming', 'previous']"
                    @click="activeTab=tab"
                    class="sm:w-36 w-[80%] mx-auto h-32 flex cursor-pointer hover:text-slate-900 flex-col gap-1 text-slate-600 items-center justify-center text-xl font-bold rounded-xl border border-primary/40 bg-gradient-to-t from-primary/20 to-slate-100 hover:from-slate-100 hover:to-primary/20 capitalize"
                >
                    <div v-if="tab=='today'" class="py-2 px-2 rounded-md">
                        <Icon name="PhClock" size="45" weight="bold" />
                    </div>
                    <div v-if="tab=='tomorrow'" class="py-2 px-2 rounded-md">
                        <Icon name="PhFastForward" size="45" weight="bold" />
                    </div>
                    <div v-if="tab=='upcoming'" class="py-2 px-2 rounded-md">
                        <Icon name="PhClockClockwise" size="45" weight="bold" />
                    </div>
                    <div v-if="tab=='previous'" class="py-2 px-2 rounded-md">
                        <Icon name="PhClockCounterClockwise" size="45" weight="bold" />
                    </div>
                    {{ tab }}
                </div>
            </div>
        </div>

        <div class="mb-5" v-if="activeTab && !activeSlot">
            <div
                v-for="doctor_chamber in doctor.doctor_chambers"
                class="mb-10"
            >
                <div class="font-semibold mb-4 text-xl text-center">
                    {{ get(doctor_chamber, 'chamber.name') }}
                </div>
                <div class="flex flex-wrap justify-center gap-5">
                    <template v-for="slot in doctor_chamber.slots || []">
                        <div
                            v-if="slot.day == today_name"
                            @click="handleSlotClick(doctor_chamber, slot)"
                            class="border border-primary/40 bg-gradient-to-t from-primary/20 to-slate-100 hover:from-slate-100 hover:to-primary/20 cursor-pointer select-none py-1 px-3"
                        >
                           {{ convert24to12(slot.from_time) }} - {{ convert24to12(slot.to_time) }}
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <div v-if="appointments.length">
            <Table
                :config="tableConfig"
                :data="appointments.data"
                :trClass="trClass"
                :searchKeys="searchKeys"
                :theadClass="theadClass"
            >
                <template #tableHeader>
                    <Button.Primary
                        outline
                        @click="handleBack(activeTab, activeSlot)"
                        class="mr-2 mb-2"
                    >
                        Back
                    </Button.Primary>
                </template>
                <template #td="{ item, key }">
                    <span :class="key == 'status' ? 'text-red-500' : ''">
                        {{ item[key] }}
                    </span>
                </template>
                <template #action="{ item }">
                    <div class="flex gap-0.5 justify-center w-full">
                        <Button.Warning @click="handleEdit(item)" class="!px-2">
                            <Icon
                                name="PhPencilSimpleLine"
                                size="20"
                                weight="bold"
                            />
                        </Button.Warning>
                        <Button.Danger @click="handleDelete(item)" class="!px-2">
                            <Icon name="PhX" size="20" weight="bold" />
                        </Button.Danger>
                    </div>
                </template>
            </Table>
        </div>
        <div v-if="!appointments.length && activeSlot" class="text-center text-slate-500 min-h-[100px] grid place-content-center">
            <span v-if="loading">
                Loading...
            </span>
            <span v-else>
                No record found
            </span>
        </div>
        <div class="flex justify-center mt-5">
            <Button.Info
                :href="route('doctor.dashboard')"
            >
                <Icon name="PhHouse" />
                Go Dashboard
            </Button.Info>
        </div>
        <Modal v-model="formModal">
            <Form
                v-if="formModal"
                :form="form"
                :patientTypes="patientTypes"
                @onSave="handleSave"
            />
        </Modal>
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
import useAppointment from './useAppointment'
import { format, nextDay, parse } from 'date-fns'
import Form from './fragments/Form.vue'

const props = defineProps({
    data: {
        type: Array,
        default: [],
    },
    doctor: {
        type: Object,
        default: {},
    },
    patientTypes: {
        type: Array,
        default: []
    },
    tomorrow: String,
    today_name: String,
});

const { appointments, payload, getAppointments, loading } = useAppointment()

const activeTab = ref(null)
const activeChamber = ref(null)
const activeSlot = ref(null)

const formModal = ref(false);

const form = useForm({
    id: null,
    patient_name: "",
    phone: "",
    age: "",
    date: null,
    patient_type_id: null,
});

const handleSlotClick = (doctor_chamber, slot) => {
    // activeChamber.value = get(doctor_chamber, 'chamber.id')
    activeChamber.value = doctor_chamber.chamber
    activeSlot.value = slot

    payload.value.chamber_id = activeChamber.value.id
    payload.value.slot_id = activeSlot.value.id
    payload.value.type = activeTab.value
    getAppointments()
}

const handleBack = (tab, slot) => {
    appointments.value = []
    if (tab && slot) {
        activeSlot.value = null
        return
    }
    if (tab) {
        activeTab.value = null
        return
    }
}

const handleEdit = (item) => {
    form.id = item.id;
    form.patient_name = item.patient_name;
    form.phone = item.phone;
    form.age = item.age;
    form.date = item.date;
    form.patient_type_id = item.patient_type_id;
    formModal.value = true;
};

const handleDelete = (id) => {
    Helper.confirm("Are you sure to delete?", () => {
        router.post(route("app.contacts.delete", id));
    });
};

const handleSave = () => {
    if(!form.id) return
    form.post(route("doctor.appointments.saveAppointments", form.id), {
        onSuccess() {
            let index = appointments.value?.data.findIndex(item => item.id == form.id)
            
            if(index > -1) {
                let item = appointments.value.data[index]
                item.id = form.id;
                item.patient_name = form.patient_name;
                item.phone = form.phone;
                item.age = form.age;
                item.date = form.date;
                item.patient_type_id = form.patient_type_id;
                appointments.value[index] = item
                formModal.value = false;
            }
        }
    });
};

const tableConfig = [
    {
        title: "Sl",
        cb({ index }) {
            return index + 1;
        },
    },
    {
        title: "Patient Name",
        key: "patient_name",
        sortable: true,
    },
    {
        title: "Phone",
        key: "phone",
        sortable: true,
    },
    {
        title: "Age",
        key: "age",
        sortable: true,
    },
    {
        title: "Date",
        key: "date",
        sortable: true,
    },
    {
        title: "Slot info",
        cb({item}){
            let from_time = ''
            let to_time = ''
            try { from_time = convert24to12(get(item, 'slot.from_time')) } catch (error) {}
            try { to_time = convert24to12(get(item, 'slot.to_time')) } catch (error) {}
            return `${from_time} - ${to_time}`
        },
        sortable: true,
    },
];

const searchKeys = ["patient_name", "phone", "age", "date", "patient_type.name"];

const theadClass = "!bg-primary !text-white";
const trClass = "hover:bg-gray-100";
</script>
