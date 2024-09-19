<template>
    <CompounderLayout title="Schedule" aside="compounder">
        
        <div>
            <div
                v-for="(chamber, index) in doctor_chamber"
                :key="index"
                class="grid gap-5 mb-10"
            >
                <h2 class="font-bold text-xl">
                    {{ get(chamber, 'chamber.name') }}
                </h2>
                <div class="flex flex-wrap gap-12 md:gap-8 mt-5">
                    <div
                        v-for="(slot, slot_index) in chamber.slots"
                        :key="slot_index"
                        class="relative py-4 px-3 border border-slate-300 shadow-md rounded flex flex-col justify-between gap-3"
                    >
                        <span 
                            class="absolute px-3 min-w-[60px] text-center rounded-full bg-white text-emerald-500 border border-emerald-500 -top-4 left-3"
                        >
                            {{ slot.appointments_count }} Apts.
                        </span>
                        <button
                            @click="selectedSlot=slot"
                            class="absolute px-2 py-1 rounded border border-primary text-primary bg-white -top-4 right-3"
                        >
                            <Icon 
                                name="PhInfo"
                                size="18"
                            />
                        </button>
                        <div>
                            <span class="font-bold">Slot {{ slot_index + 1 }}:</span>
                            From {{ convert24to12(slot.from_time) }} To {{ convert24to12(slot.to_time) }}
                        </div>
                        <div class="flex flex-wrap gap-3 pb-5">
                            <span class="font-bold">Start At:</span>
                            {{ modify(slot) }}
                            <input 
                                type="time"
                                :value="get_modifier(slot)"
                                @input="e => slot.updated_from_time = e.target.value"
                            />
                            <button
                                @click="handleSave(slot, chamber.user_id)"
                                class="py-0.5 px-2 rounded bg-primary text-white"
                            >
                                Save
                            </button>
                        </div>
                        <div class="flex absolute -bottom-3 left-1/2 -translate-x-1/2 justify-center">
                            <Link
                                :href="route('compounder.schedule.visitation', slot.id)"
                                class="py-0.5 px-2 z-10 w-[110px] text-center bg-emerald-500 text-white rounded  shadow-md"
                            >
                                View Details
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="!doctor_chamber.length" class="text-center font-semibold text-slate-500 text-xl">
                No Schedule found
            </div>

        </div>

        <Modal v-model="selectedSlot">
            <div v-if="selectedSlot" class="w-[500px] bg-white rounded-md">
                <div class="p-3 border-b font-semibold text-xl">History</div>
                <div class="py-4 px-3">
                    <ul class="list-disc">
                        <li
                            v-for="(modified, index) in selectedSlot.modifier"
                            :key="index"
                        >
                            Changed to {{ convert24to12(modified.from_time) }} by 
                            <strong class="font-semibold">{{ get(modified, 'user.name') }}</strong>
                            At
                            <strong class="font-semibold">{{ modified.created_at }}</strong>
                        </li>
                    </ul>
                    <div v-if="!selectedSlot.modifier.length" class="text-center text-slate-500 font-semibold">
                        No information found
                    </div>
                </div>
            </div>
        </Modal>

    </CompounderLayout>
</template>

<script setup>
import CompounderLayout from "@/Layouts/CompounderLayout.vue";
import { get } from 'lodash'
import { Icon, Modal } from '@/plugins/ui'
import Helper, { convert24to12, notify } from '@/helper'
import { useForm, Link } from '@inertiajs/vue3'
import { format } from 'date-fns'
import { ref } from 'vue'

const props = defineProps({
    doctor: {
        type: Object,
        default: {},
    },
    doctor_chamber: {
        type: Array,
        default: [],
    },
    off_days: {
        type: Array,
        default: [],
    },
    day: {
        type: String,
        default: '',
    },
    date: {
        type: String,
        default: '',
    },
});

const selectedSlot = ref(false)

const modify_form = useForm({
    slot_id: null,
    day: props.day,
    date: props.date,
    from_time: null,
    to_time: null,
    doctor_id: null,
})

const getFormattedDate = (date) => {
    let op = ''
    try {
        op = format(new Date(date), 'PP')
    } catch (error) {}
    return op
}

const get_modifier = (slot) => {
    let op = null
    try {
        if (slot.modifier.length) {
            op = get(slot.modifier, '[0].from_time')
            console.log(op);
        } else {
            op = slot.from_time
        }
    } catch (error) {}
    return op
}

const modify = (slot) => {
    slot.updated_from_time = slot.from_time
    return null
}

const handleSave = (slot, doctor_id) => {
    if (slot.updated_from_time == slot.from_time) {
        notify.info('Nothing changed!')
        return
    }
    Helper.confirm('Are you sure to update start time?', () => {
        modify_form.slot_id = slot.id
        modify_form.from_time = slot.updated_from_time
        modify_form.doctor_id = doctor_id
        modify_form.post(route('compounder.schedule.update'), {
            onFinish() {
                modify_form.reset()
            }
        })
    })
}

</script>
