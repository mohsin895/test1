<template>
    <DoctorLayout title="My Calendar">
        <div class="mb-8 border-b  text-2xl font-bold"></div>
        <div class="mb-7">
            
            <div class="flex flex-col md:flex-row justify-between  mb-5">
                <div class="font-semibold  md:text-2xl text-[22px] ">
                    Available Dates
                </div>
                <div class="flex items-center gap-2 font-medium text-slate-900 ">
                   For
                    <Input.Primary v-model="weeks" type="number" class="border w-24" />
                    weeks
                </div>
            </div>
            
            <div class="grid gap-2 grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 justify-center">
                <div
                    v-for="(item, index) in active_dates"
                    :key="index"
                    @click="handleDateClick(item)"
                    class="block cursor-pointer relative select-none"
                >
                    <div
                        class="bg-white hover:bg-slate-100 rounded-md shadow h-full flex flex-col gap-1 md:flex-row text-center md:text-left justify-between items-center relative border border-gray-300 py-4 -ml-px -mt-px px-3 select-none"
                        :title="item.date"
                    >
                        {{ item.date }}
                        <div v-if="hasOffDay(item)" class="gap-1 h-full flex flex-col justify-center items-center">
                            <div
                                v-for="(chamber, index) in doctor.doctor_chambers"
                                :key="index"
                                class="flex gap-0.5"
                            >
                                <template v-for="(slot, index) in chamber.slots">
                                    <span
                                        v-if="item.day == slot.day"
                                        class="w-3 h-3 rounded-full"
                                        :class="isActiveSlot(slot, item) ? 'bg-green-700' : 'bg-red-500'"
                                    ></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div class="text-2xl font-bold">Control Appointments</div>
                <div class="mt-5 pb-10">
                    
               
<div class="flex items-center mb-2">
    <input id="default-radio-1" checked type="radio" value="fixed" v-model="appointmentControlTab" name="default-radio" @click="appointmentControlTab='fixed'" class="w-5 h-5  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
    <label for="default-radio-1" class="ms-2 text-md font-semibold text-gray-900 dark:text-gray-300">Fixed</label>
</div>
<span>Till which date you want to open appointments to book?</span>
<div class="flex flex-1 gap-5">
    <span><Input.DatePicker
                           
                           v-model="appointment_control_form.appointment_end_date"
                           placeholder="Select a date"
                     class="mt-2 mb-2 "  /></span>
    <span class="mt-4"> {{totalDay()}} left.</span>
</div>

<div v-if="appointment_control_form.errors.appointment_end_date" class="text-red-500 text-xs">
                                The Date field is required.
                            </div>
<div class="flex items-center mt-2 mb-2">
    <input  id="default-radio-2" type="radio" value="dynamic" v-model="appointmentControlTab" click="appointmentControlTab='dynamic'" name="default-radio" class="w-5 h-5  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
    <label for="default-radio-2" class="ms-2 text-md font-semibold text-gray-900 dark:text-gray-300">Dynamic</label>
</div>

<span>For how many days will be increased after a daynamic period ? </span>

   
    <Input.Primary
                           
                            type="number"
                            v-model="appointment_control_form.appointment_week_limit"
                            placeholder="Enter days"
                            class="mt-2 mb-2 max-w-[250px] "  />



                            <div v-if="appointment_control_form.errors.appointment_week_limit" class="text-red-500 text-xs">
                                The Days field is required.
                            </div>
                        
                            <span> Today is {{dateFormateWithDay(currentdate)}}. Selected daynamic period will end on {{ nextDate() }}.</span>
<br/>

                        <Button.Primary 
                                :loading="appointment_control_form.processing" 
                                :disabled="appointment_control_form.processing"
                                @click="save_appointment_control"
                                class="mt-4 " >
                                Save
                            </Button.Primary>
                    <!-- <div class="max-w-[400px] md:mx-auto mt-4 border p-5 border-primary  grid gap-5 rounded-md shadow-xl shadow-primary/10">
                        <div class="flex justify-center">
                          

                            <button 
                                @click="appointmentControlTab='fixed'"
                                class="py-2 px-4 border"
                                :class="appointmentControlTab=='fixed' ? 'bg-primary text-white border-primary' : 'border-slate-400'"
                            >
                                Fixed
                            </button>
                            <button
                                @click="appointmentControlTab='dynamic'"
                                class="py-2 px-4 border"
                                :class="appointmentControlTab=='dynamic' ? 'bg-primary text-white border border-primary' : 'border-slate-400 border-l-0'"
                            >
                                Dynamic
                            </button>
                        </div>
                        <Input.Primary
                            v-if="appointmentControlTab=='dynamic'"
                            type="number"
                            v-model="appointment_control_form.appointment_week_limit"
                            placeholder="Enter weekdays"
                        />
                        <Input.DatePicker
                            v-if="appointmentControlTab=='fixed'"
                            v-model="appointment_control_form.appointment_end_date"
                            placeholder="Select end date"
                        />
                        <div class="flex justify-end">
                            <Button.Primary 
                                :loading="appointment_control_form.processing" 
                                :disabled="appointment_control_form.processing"
                                @click="save_appointment_control"
                            >
                                Save
                            </Button.Primary>
                        </div>
                    </div> -->
                </div>
            </div>

        </div>

        <Modal v-model="showConfigureModal" @close="handleClose()">
            <div v-if="showConfigureModal" class="bg-white rounded w-[300px] md:w-[500px]">
                <div class="py-3 px-5 font-semibold border-b flex items-center justify-between">
                    {{ selectedDate.date }}
                </div>
                <div class="px-5 py-3">
                    <div class="mb-3">
                        <label class="select-none flex items-center gap-1.5 border py-1 px-2 border-primary/50">
                            <input v-model="form.is_full_day" @change="handleIsFullDay(form.is_full_day)" type="checkbox" class="mt-[-2px]" />
                            Full day off
                        </label>
                    </div>
                    <div 
                        v-for="(doctor_chamber, index1) in doctor.doctor_chambers"
                        :key="index1"
                        class="mb-5"
                    >
                        <div class="mb-3 font-semibold">
                            ({{ index1 + 1 }}) {{ get(doctor_chamber, 'chamber.name') }}
                        </div>
                        <div class="grid gap-2">
                            <template
                                v-for="(slot, index2) in doctor_chamber.slots"
                            >
                                <label
                                    v-if="slot.day == selectedDate.day"
                                    class="flex items-center gap-2 pl-6 select-none"
                                >
                                    <input 
                                        type="checkbox" 
                                        @input="handleSelectSlot(slot, doctor_chamber)" 
                                        :value="slot.id" 
                                        :checked="isSelected(slot, doctor_chamber)" 
                                        class="mt-[-2px] peer checked:accent-red-500"
                                    />
                                    <span class="font-bold text-green-700 peer-checked:text-red-500">
                                        {{ convert24to12(slot.from_time) }} -
                                        {{ convert24to12(slot.to_time) }}
                                    </span>
                                </label>
                            </template>
                            <div v-if="doctor_chamber.slots.filter(i => i.day == selectedDate.day).length == 0" class="pl-6 text-gray-400">No slot found!</div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button @click="handleSave" class="py-1 px-3 bg-primary text-white">Save</button>
                    </div>

                </div>
            </div>
        </Modal>

    </DoctorLayout>
</template>

<script setup>
import DoctorLayout from "@/Layouts/DoctorLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import { Modal, Button } from "@/plugins/ui";
import { Input } from '@/plugins/form'
import { ref, computed, onMounted, toRaw, watch } from "vue";
import { convert24to12,dateFormateWithDay } from '@/helper'
import { get } from 'lodash'
const today = new Date();
const currentdate = ref(today.toISOString().split('T')[0]);
const totalDay = () => {
    if (appointment_control_form.appointment_end_date && currentdate.value) {
        const endDate = new Date(appointment_control_form.appointment_end_date);
        const startDate = new Date(currentdate.value);
        const diffTime = Math.abs(endDate - startDate); 
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
        return diffDays + " days"; 
    }
    return "0 days"; 
}
const nextDate = () => {
    const today = new Date(); 
    const daysToAdd = appointment_control_form.appointment_week_limit  + 1; 
    const resultDate = new Date(today); 
    resultDate.setDate(today.getDate() + daysToAdd); 

    const day = resultDate.getDate(); 
    const month = resultDate.toLocaleString('en-US', { month: 'long' }); 
    const year = resultDate.getFullYear(); 

    return `${day} ${month}, ${year}`; 
}
const props = defineProps({
    doctor: {
        type: Object,
        default: {},
    },
    active_dates: {
        type: Array,
        default: []
    },
    slots: {
        type: Array,
        default: []
    },
    weeks: {
        type: Number,
        default: 12
    },
});

const weeks = ref(props.weeks)
const appointmentControlTab = ref('fixed')
// const fixed= ref("fixed");
// const dynamic = ref('dynamic');

let timeout = null;

watch(weeks, () => {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
        router.visit(route('doctor.calender', {
            weeks: weeks.value
        }))
    }, 1000)
})

const form = useForm({
    off_days: [],
    day: null,
    date: null,
    is_full_day: false
})

const appointment_control_form = useForm({
    appointment_end_date: null,
    appointment_week_limit: null,
    is_fixed: true,
})

onMounted(() => {
    let doctor = props.doctor
    appointment_control_form.is_fixed = doctor.is_fixed
    appointment_control_form.appointment_end_date = doctor.appointment_end_date
    appointment_control_form.appointment_week_limit = doctor.appointment_week_limit
    
    if (appointment_control_form.is_fixed) {
        appointmentControlTab.value = 'fixed'
    } else {
        appointmentControlTab.value = 'dynamic'
    }

})

const save_appointment_control = () => {
    appointment_control_form.is_fixed = appointmentControlTab.value == 'fixed'
    if (appointment_control_form.is_fixed) {
        appointment_control_form.appointment_week_limit = null
    } else {
        appointment_control_form.appointment_end_date = null
    }

    appointment_control_form.post(route('doctor.save_appointment_control'))
}

const selectedDate = ref({})

const showConfigureModal = ref(false)

const isSelected = (slot, doctor_chamber) => {
    let found = form.off_days.find(i => i.doctor_chamber_id == doctor_chamber.id)
    if (found && found.slots.includes(slot.id)) {
        return true
    }
    return false
}

const handleSelectSlot = (slot, doctor_chamber) => {
    let found = form.off_days.find(item => item.doctor_chamber_id === doctor_chamber.id);
    if (found) {
        if (found.slots.includes(slot.id)) {
            found.slots = found.slots.filter(i => i !== slot.id);
        } else {
            found.slots.push(slot.id);
        }
        if (found.slots.length === 0) {
            form.off_days = form.off_days.filter(i => i.doctor_chamber_id !== found.doctor_chamber_id);
        }
    } else {
        form.off_days.push({
            doctor_chamber_id: doctor_chamber.id,
            slots: [slot.id]
        });
    }
    checkIsFullDay()
};

const getData = () => {
    let tempArr = []
    let slotCount = 0
    props.doctor.doctor_chambers.forEach(item => {
        item.slots.forEach(slot => {
            if (slot.day == selectedDate.value.day) {
                let found = tempArr.find(i => i.doctor_chamber_id == item.id)
                if (!found) {
                    tempArr.push({
                        doctor_chamber_id: item.id,
                        slots: [slot.id]
                    });
                    slotCount++
                } else {
                    slotCount++
                    found.slots.push(slot.id)
                }
            }
        })
    })
    return {
        tempArr,
        slotCount
    }
}

const checkIsFullDay = () => {
    const { slotCount, tempArr } = getData()
    let form_slots = form.off_days.flatMap(i => i.slots)
    if (slotCount == form_slots.length) {
        form.is_full_day = true
    } else {
        form.is_full_day = false
    }
    return tempArr
}

const handleIsFullDay = (is_full_day) => {
    if (is_full_day) {
        form.off_days = getData().tempArr
    } else {
        form.off_days = []
    }
}

const handleClose = () => {
    selectedDate.value = {}
    showConfigureModal.value = false
    form.reset()
}

const handleDateClick = (item) => {
    selectedDate.value = item
    showConfigureModal.value = true
    form.reset()
    const { doctor } = props
    let offDayData = doctor.off_days.find(item => item.date == selectedDate.value._date)
    if (offDayData) {
        form.off_days = offDayData.chamber_slot
        form.is_full_day = Boolean(offDayData.is_full_day)
        checkIsFullDay()
    }
}


const isActiveSlot = (slot, item) => {
    return props
                .doctor
                .off_days
                .filter(i => {
                    return i.date == item._date && i.day == item.day
                })
                .flatMap(i => i.chamber_slot)
                .find(i => {
                    return i.doctor_chamber_id == slot.doctor_chamber_id
                            && i.slots.includes(slot.id)
                }) ? false : true
}

const hasOffDay = (item) => {
    let found = props.doctor.off_days.find(i => {
        return i.date == item._date && item.day == i.day
    })
    return found ? true : false
}


const handleSave = () => {
    if (!selectedDate.value) return
    form.day = selectedDate.value.day
    form.date = selectedDate.value._date
    form.post(route('doctor.save_calender'), {
        onSuccess(e) {
            if (!Object.values(e.props.errors).length) {
                handleClose()
            }
        }
    })
}

</script>
