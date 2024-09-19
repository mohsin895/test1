<template>
    <div class="bg-white rounded-md md:min-w-[500px]">
        <h2 class="font-bold text-xl border-b p-4">
            Select slot time for <span class="text-primary capitalize">{{ item_day.day }}</span>
        </h2>
        <div class="p-4 relative">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div>Start Time <span class="text-red-500">*</span></div>
                    <input type="time" v-model="slot.from" class="border py-1 px-2 w-full" />
                    <div v-if="slot.errors.from" class="text-red-500 mt-0.5">{{ slot.errors.from }}</div>
                </div>
                <div>
                    <div>End Time <span class="text-red-500">*</span></div>
                    <input type="time" v-model="slot.to" class="border py-1 px-2 w-full" />
                    <div v-if="slot.errors.to" class="text-red-500 mt-0.5">{{ slot.errors.to }}</div>
                </div>
                <div class="col-span-2">
                    <div>Limit <span class="text-red-500">*</span></div>
                    <input v-model="slot.limit" type="number" class="border py-1 px-2 w-full" placeholder="Limit" />
                </div>
            </div>
            <div class="flex mt-4 justify-end">
                <Button.Primary @click="addSlot(item_day, index)">
                    {{ index > -1 ? 'Update' : 'Add' }}
                </Button.Primary>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Button } from '@/plugins/ui'
import DatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps({
    item_day: {
        type: Object,
        default: {}
    },
    index: {
        type: Number,
        default: -1
    }
})

const slot = ref({
    id: null,
    from: null,
    to: null,
    day: null,
    limit: 0,
    errors: {
        from: null,
        to: null,
    }
})

const emit = defineEmits(['update'])

const addSlot = (item_day, index) => {
    slot.value.errors.from = ''
    slot.value.errors.to = ''
    if (!slot.value.from) {
        slot.value.errors.from = 'Required'
    }

    if (!slot.value.to) {
        slot.value.errors.to = 'Required'
    }

    if (!slot.value.from || !slot.value.to) {
        return
    }
    if (index > -1) {
        item_day.slot[index] = {
            from: slot.value.from,
            to: slot.value.to,
            day: slot.value.day,
            limit: slot.value.limit,
            id: slot.value.id,
        }
    } else {
        item_day.slot.push({
            from: slot.value.from,
            day: slot.value.day,
            limit: slot.value.limit,
            to: slot.value.to,
            id: slot.value.id
        })
    }
    item_day.popup = false
    emit('update', item_day)
}

onMounted(() => {
    slot.value.day = props.item_day.day
    if (props.index > -1) {
        let item = props.item_day.slot[props.index]
        slot.value.from = item.from
        slot.value.to = item.to
        slot.value.day = item.day
        slot.value.limit = item.limit
        slot.value.id = item.id
    }
})

</script>