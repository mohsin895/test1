<template>

    <!-- <div class="relative">
        <Button.Primary
            @click="showDropDown=!showDropDown"
            ref="dropButton"
        >
            Select Chamber
        </Button.Primary>
        <div v-if="showDropDown" class="bg-white absolute shadow-xl border top-full max-w-[300px] max-h-[400px] overflow-y-auto">
            <div
                v-for="(item, index) in $page.props.chambers"
                :key="index"
                @click="handleSelect(item)"
                class="py-1 px-4 border-b select-none cursor-pointer hover:bg-slate-100"
            >
                {{ item.name }}
            </div>
        </div>
    </div> -->

    <!-- <div class="mt-5">
        <div
            v-for="day in $page.props.doctor.doctor_details.weekdays"
            :key="day"
            class="capitalize mb-10"
        >
            <div class="font-bold text-xl max-w-[400px] flex justify-between items-center border-b pb-4">
                {{ day }}
                <Button.Primary>Add slot</Button.Primary>
            </div>
            <div>
            </div>
        </div>
    </div> -->

    <!-- <button class="w-[100px] h-[100px] border border-slate-400 bg-slate-100">
        Add slot
    </button> -->

    <div class="hidden top-0 left-0 right-0 bottom-0 z-20 bg-black/40 flex items-center justify-center">
        <div class="bg-white w-full max-w-screen-md p-5 rounded-md">
            <h2 class="text-xl font-bold mb-5">
                Select week
            </h2>

            <!-- <div class="relative mb-5">
                <Button.Primary
                    @click="showDropDown=!showDropDown"
                    outline
                    ref="dropButton"
                >
                    Select Chamber
                </Button.Primary>
                <div v-if="showDropDown" class="bg-white absolute shadow-xl border top-full max-w-[300px] max-h-[400px] overflow-y-auto">
                    <div
                        v-for="(item, index) in $page.props.chambers"
                        :key="index"
                        @click="handleSelect(item)"
                        class="py-1 px-4 border-b select-none cursor-pointer hover:bg-slate-100"
                    >
                        {{ item.name }}
                    </div>
                </div>
            </div> -->
            <Select.Primary
                :options="$page.props.chambers"
                item-key="id"
                item-value="name"
                label="Chamber"
                v-model="selected_chamber"
                wrapper-class="mb-5"
            />

            <ul class="grid w-full gap-6 grid-cols-1 md:grid-cols-3">
                <li
                    v-for="item in $page.props.week_days"
                    :key="item.id"
                >
                    <input type="radio" v-model="selected_week" :id="item" :value="item" class="hidden peer" />
                    <label 
                        :for="item"
                        class="select-none inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary hover:text-gray-600 peer-checked:text-gray-600 hover:bg-slate-100"
                    >
                        <div class="block">
                            <div class="w-full text-lg font-semibold capitalize">{{ item }}</div>
                        </div>
                    </label>
                </li>
            </ul>

            <div class="text-xl font-bold my-5 flex justify-between items-center border-b pb-3">
                Slots
                <Button.Primary>+</Button.Primary>
            </div>

            <div class="flex gap-3 items-center">
                <div class="grid grid-cols-2 gap-5 flex-1">
                    <div class="flex flex-col gap-1">
                        Start Time
                        <input type="time" class="border p-1" />
                    </div>
                    <div class="flex flex-col gap-1">
                        End Time
                        <input type="time" class="border p-1" />
                    </div>
                </div>
                <Button.Danger class="mt-7">x</Button.Danger>
            </div>

            <div class="mt-5 flex justify-end">
                <Button.Primary>Add</Button.Primary>
            </div>

        </div>
    </div>

    <NextPrev @action="move => handleSave(move)" />

</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { useConfig } from '../useConfig'
import { Input, Select } from '@/plugins/form'
import { Button } from '@/plugins/ui'
import NextPrev from '../NextPrev.vue';
import { onMounted, ref, computed } from 'vue'
import { onClickOutside } from '@vueuse/core'

const { handleNextPrev } = useConfig()

const form = useForm({
    weekdays: [],
})

const showDropDown = ref(false)
const dropButton = ref()
const selected_chambers = ref(null)
const selected_week = ref(null)
const selected_slots = ref([])

onClickOutside(dropButton, (event) => {
    showDropDown.value = false
})

const handleSelect = (item) => {
    let index = selected_chambers.value.indexOf(item.id)
    if (index > -1) {
        selected_chambers.value.splice(index, 1)
        return
    }

    selected_chambers.value.push(item.id)
}

onMounted(() => {
    let pageProps = usePage().props
    // form.weekdays = get(pageProps, 'doctor.doctor_details.weekdays') || []
})

const handleSave = (move) => {
    if (move > 0) {
        let pageProps = usePage().props
        form.post(route('app.doctors.save_weekdays', pageProps.doctor.id), {
            onSuccess(e) {
                if (Object.keys(e.props.errors).length==0) {
                    handleNextPrev(move)
                }
            }
        })
    } else {
        handleNextPrev(move)
    }
}

</script>