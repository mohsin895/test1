<template>
    <DoctorLayout title="Configuration">
        <div class="flex flex-col">
            <div class="p-5 border-b border-gray-400 flex items-center gap-4 w-full overflow-x-auto">
                <!-- @click="activeTab=item.id" -->
                <button
                    v-for="(item, index) in steps"
                    :key="index"
                    @click="activeTab=item.id"
                    :class="activeTab==item.id ? 'bg-primary text-white' : ''"
                    class="flex justify-between p-2 rounded text-left w-full hover:bg-primary hover:text-white"
                >
                    {{ item.title }}
                    <Icon v-if="item.id !== 5" name="PhArrowRight" size="24" />
                </button>
            </div>
            <div class="flex-1 p-5">
                <component :is="steps[activeTab].component" :doctor="doctor" />
            </div>
        </div>
    </DoctorLayout>
</template>

<script setup>
import DoctorLayout from "@/Layouts/DoctorLayout.vue";
import { Button, Icon } from '@/plugins/ui'
import { useConfig } from './useConfig'

const props = defineProps({
    designations: Array,
    specializations: Array,
    degrees: Array,
    chambers: Array,
    week_days: Array,
    medicals: Array,
    doctor: {
        type: Object,
        default: {}
    },
});

const { handleNextPrev, activeTab, steps } = useConfig()

</script>
