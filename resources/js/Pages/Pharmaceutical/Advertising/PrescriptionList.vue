<template>
    <PharmaceuticalLayout title="Prescriptions">
        <div
            class="w-full"
        >
            <AdInformation
                :doctorAdvertise="doctorAdvertise"
            />

            <div
                class="mb-4 border border-slate-200 rounded shadow p-4 text-center"
            >
                <div
                    v-for="chamber in doctorChamber"
                    :key="chamber.id"
                >
                    <label class="mb-2 block font-medium"
                        >Chamber :
                        <span class="font-semibold text-xl">{{
                            chamber.chamber.name
                        }}</span></label
                    >

                    <div
                        v-for="(slot, index) in chamber.slots"
                        :key="index"
                        class="text-lg py-5"
                    >
                        <span
                            >{{ `Slot - ${index + 1}` }}
                            <div class="text-base font-semibold">
                                {{ convert24to12(slot.from_time) }} -
                                {{ convert24to12(slot.to_time) }}
                                <div v-if="!slot.appointments.length">
                                    <span class="text-red-500">---</span>
                                </div>
                                <div
                                    class="flex flex-wrap justify-center items-center"
                                >
                                    <div
                                        v-for="appointment in slot.appointments"
                                        :key="appointment.id"
                                        class="flex justify-center items-center flex-col m-2"
                                    >
                                        <div v-if="appointment.present == 1">
                                            <div
                                                class="w-[150px] h-[150px] flex items-center justify-center border border-slate-200 mb-2 rounded-md"
                                            >
                                                <img
                                                    v-if="appointment.media"
                                                    :src="get(appointment, 'media_file.path')"
                                                    class="w-full h-full object-cover cursor-pointer"
                                                    @click="showSingle(get(appointment, 'media_file.path'))"
                                                />
                                                <span v-else class="text-red-500 font-semibold">---</span>
                                            </div>
                                        </div>
                                        <div
                                            v-else-if="appointment.present == 2"
                                        >
                                            <div class="w-[150px] h-[150px] flex items-center justify-center border border-slate-200 mb-2 rounded-md">
                                                <span class="text-blue-500 font-semibold text-lg">
                                                    Skipped
                                                </span>
                                            </div>
                                        </div>
                                        <div v-else="appointment.present === 0">
                                            <div
                                                class="w-[150px] h-[150px] flex items-center justify-center border border-slate-200 mb-2 rounded-md"
                                            >
                                                <span class="text-red-500 font-semibold text-lg">Absent</span>
                                            </div>
                                        </div>

                                        <div
                                            class="flex justify-center items-center"
                                        >
                                            <div
                                                class="w-8 h-8 border border-green-400 rounded-full shadow-md flex items-center justify-center"
                                                v-if="appointment.present === 1"
                                            >
                                                <span class="font-bold">
                                                    {{ appointment.serial_no }}
                                                </span>
                                            </div>
                                            <div
                                                class="w-8 h-8 border border-blue-400 rounded-full shadow-md flex items-center justify-center"
                                                v-else-if="appointment.present === 2"
                                            >
                                                <span class="font-bold">
                                                    {{ appointment.serial_no }}
                                                </span>
                                            </div>
                                            <div
                                                class="w-8 h-8 border border-red-400 rounded-full shadow-md flex items-center justify-center"
                                                v-else="appointment.present === 0"
                                            >
                                                <span class="font-bold">{{ appointment.serial_no }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <VueEasyLightbox
            :visible="visibleRef"
            :imgs="imgsRef"
            :index="indexRef"
            @hide="onHide"
        >
            <template v-slot:toolbar="{ toolbarMethods }">
                <div v-if="imgsRef" class="px-5 py-4 flex justify-center gap-2">
                    <a 
                        :href="imgsRef" 
                        download
                        class="flex items-center gap-1 py-1 px-4 bg-green-500 text-white rounded hover:opacity-90"
                    >
                        <Icon name="PhDownloadSimple" size="20" />
                        Download
                    </a>
                    <Button.Primary 
                        @click="toolbarMethods.zoomIn" 
                        outline
                    >
                        <Icon name="PhPlus" size="20" />
                    </Button.Primary>
                    <Button.Primary 
                        @click="toolbarMethods.zoomOut"
                        outline
                    >
                        <Icon name="PhMinus" size="20" />
                    </Button.Primary>
                </div>
            </template>
        </VueEasyLightbox>
    </PharmaceuticalLayout>
</template>

<script setup>
import PharmaceuticalLayout from "@/Layouts/PharmaceuticalLayout.vue";
import { get } from "lodash";
import Helper, { convert24to12 } from "@/helper";
import AdInformation from "./fragments/AdInformation.vue"
import { ref } from 'vue'
import { Button, Icon } from '@/plugins/ui'
import VueEasyLightbox from 'vue-easy-lightbox'
defineProps({
    doctorAdvertise: {
        type: Object,
        default: {},
    },
    doctorChamber: {
        type: Object,
        default: {},
    },
});
const visibleRef = ref(false)
const indexRef = ref(0)
const imgsRef = ref()
const onShow = () => {
      visibleRef.value = true
}
const showSingle = (url) => {
    imgsRef.value = url
    onShow()
}
const onHide = () => (visibleRef.value = false)
</script>
