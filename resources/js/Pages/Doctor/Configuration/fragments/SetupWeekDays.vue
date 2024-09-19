<template>
    <div
        v-for="(item, index) in payload"
        :key="index"
        class="shadow-lg border p-5 rounded-md mb-8"
    >
        <div class="mb-4 flex gap-4 flex-wrap pb-3">
            <div class="font-bold text-xl">
                {{ get(item, "chamber.name") }}
            </div>
            <div class="flex gap-3 flex-wrap flex-1">
                <label 
                    v-for="day in $page.props.week_days"
                    :key="day"
                    class="flex"
                >
                    <input type="checkbox" @input="handleInput(day, index, payload)" name="weekdays[]" :value="day" :checked="item.weekdays.includes(day)" class="peer hidden" />
                    <div
                        class="capitalize border border-slate-300 py-1 peer-checked:bg-primary peer-checked:text-white px-4 rounded cursor-pointer bg-white hover:bg-primary hover:border-primary hover:text-white"
                    >
                        {{ day }}
                    </div>
                </label>
            </div>
        </div>

        <div class="min-h-[200px] bg-white rounded-md border border-slate-400 p-4">
            <div
                v-for="(item_day, index) in item.weekdays_with_slot"
                class="text-base px-4 border-b py-3 flex items-center gap-2"
                :class="(index + 1) % 2 == 0 ? 'bg-slate-50' : ''"
            >
                <div class="capitalize font-bold text-xl w-[120px] border-r mr-5 border-slate-400">
                    {{ item_day.day }}
                </div>
                <div class="flex flex-1 items-center flex-wrap gap-3">
                    <div v-for="(slot, index) in item_day.slot" :key="index" class="flex">
                        <button @click="() => {
                            selectedIndex = index
                            item_day.popup = true
                        }" class="py-1 px-4 border border-r-0 border-slate-400 rounded-l-full hover:bg-slate-200">
                            {{ convert24to12(slot.from) }} - {{ convert24to12(slot.to) }}
                        </button>
                        <button @click="removeSlot(item_day.slot, index)" class="border border-slate-400 rounded-r-full px-3 text-red-500 hover:bg-red-200 hover:border-red-500">
                            <Icon name="PhX" size="18" />
                        </button>
                    </div>
                </div>
                <div class="flex gap-0.5">
                    <button @click="item_day.popup = true" class="rounded-full w-[34px] h-[34px] grid place-content-center text-white bg-primary">
                        <Icon name="PhPlus" size="18" />
                    </button>
                    <!-- <button @click="handleInput(item_day.day, $page.props.week_days.indexOf(item_day.day), payload)" class="rounded-full w-[34px] h-[34px] grid place-content-center text-white bg-red-500">
                        <Icon name="PhX" size="18" />
                    </button> -->
                </div>
                <Modal v-model="item_day.popup">
                    <SlotDatePicker :index="selectedIndex" :item_day="item_day" @update="val => handleUpdate(item_day, val)" v-if="item_day.popup" />
                </Modal>
            </div>
        </div>
    </div>

    <NextPrev 
        @action="(move) => handleSave(move)"
        nextButtonText="Save & Publish"
    />
</template>

<script setup>
import { useForm, usePage, router } from "@inertiajs/vue3";
import { useConfig } from "../useConfig";
import { Input } from "@/plugins/form";
import NextPrev from "../NextPrev.vue";
import { onMounted, ref } from "vue";
import { Icon, Modal, Button } from "@/plugins/ui";
import { get } from "lodash";
import SlotDatePicker from '../components/SlotDatePicker.vue'
import Helper, { convert24to12, convert12to24 } from "@/helper";

const props = defineProps({
    doctor: {
        type: Object,
        default: {}
    }
})

const { handleNextPrev } = useConfig();

const form = useForm({
    weekdays: [],
    payload: []
});

const payload = ref([])

const selectedIndex = ref(-1)

const handleInput = (day, index, payload) => {
    let item = payload[index]
    let indexOf = item.weekdays.indexOf(day)
    if (indexOf < 0) {
        item.weekdays.push(day)
        item.weekdays_with_slot.push({
            id: null,
            day: day,
            popup: false, 
            limit: 50,
            slot: []
        })
    } else {
        item.weekdays.splice(indexOf, 1)
        item.weekdays_with_slot.splice(indexOf, 1)
    }
}

const handleUpdate = (item_day, val) => {
    item_day.slot = val.slot
    item_day.popup = false
    selectedIndex.value = -1
}

const getSlots = (slotArr) => {
    return slotArr.map(item => {
        return {
            from: item.from_time,
            to: item.to_time,
            day: item.day,
            limit: item.limit,
            id: item.id
        }
    }) || []
}

const getWeekDayWithSlots = (item) => {
    let slot_array = []
    item.weekdays.forEach(day => {
        let slots = item.slots.filter(i => i.day == day)
        slot_array.push({
            day: day,
            popup: false,
            limit: 50,
            slot: getSlots(slots)
        })
    })
    return slot_array
}

const preparePayload = (chamber) => {
    let op = chamber || []
    op.map(item => {
        item.weekdays = item.weekdays || []
        item.weekdays_with_slot = getWeekDayWithSlots(item)
        item.popup = false
        return item
    })
    // console.log(op);
    payload.value = op
}

const removeSlot = (slots, index) => {
    Helper.confirm('Are you sure to remove this slot?', () => {
        slots.splice(index, 1)
    })
}

onMounted(() => {
    let pageProps = usePage().props;
    form.weekdays = get(pageProps, "doctor.doctor_details.weekdays") || [];
    preparePayload(props.doctor.doctor_chambers)
});

const handleSave = (move) => {
    let pageProps = usePage().props;
    form.payload = payload.value
    form.post(route("doctor.save_weekdays"), {
        onSuccess(e) {
            if (!Object.values(e.props.errors).length) {
                router.visit(route('doctor.calender'))
            }
        }
    })
};

</script>
<!-- 

{
    "weekdays": [],
    "payload": [
        {
            "id": 1,
            "user_id": 3,
            "chamber_id": 1,
            "weekdays": [
                "sunday"
            ],
            "note": null,
            "created_at": "2023-10-16T05:31:34.000000Z",
            "updated_at": "2023-10-27T12:37:44.000000Z",
            "chamber": {
                "id": 1,
                "name": "Popular Diagnostic Centre Ltd., Mymensingh",
                "map_location": "<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14493.849595179923!2d90.4087841!3d24.7453303!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x37564fa682e7f843%3A0xff705ad5e320e866!2sPopular%20Diagnostic%20Centre%20Ltd.%2C%20Mymensingh!5e0!3m2!1sen!2sbd!4v1696335366310!5m2!1sen!2sbd\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>",
                "city": "Mymensingh",
                "division": "Mymensingh",
                "address": "255 Charpara Rd, Mymensingh",
                "created_by": null,
                "updated_by": null,
                "deleted_by": null,
                "settings": null,
                "deleted_at": null,
                "created_at": null,
                "updated_at": null
            },
            "slots": [],
            "weekdays_with_slot": [
                {
                    "day": "sunday",
                    "popup": false,
                    "limit": 50,
                    "slot": [
                        {
                            "from": "19:43",
                            "day": null,
                            "limit": 50,
                            "to": "19:45"
                        }
                    ]
                }
            ],
            "popup": false
        }
    ]
} -->