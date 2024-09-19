<template>
    <div class="mt-5 mx-auto flex justify-between">
        <button @click="$emit('action', -1)" class="border border-gray-400 py-3 px-5 rounded-md flex justify-between items-center gap-5 hover:bg-slate-100 hover:border-primary">
            <Icon name="PhCaretCircleLeft" size="25" />
            <div class="text-xl font-bold">
                {{ previousButtonText }}
            </div>
        </button>
        <button @click="$emit('action', 1)" class="border border-gray-400 py-3 px-5 rounded-md flex justify-between items-center gap-5 hover:bg-slate-100 hover:border-primary">
            <div class="text-xl font-bold">
                {{ nextButtonText }}
            </div>
            <Icon name="PhCaretCircleLeft" class="rotate-180" size="25" />
        </button>
    </div>
</template>

<script setup>
import { useConfig } from './useConfig'
import { Icon } from '@/plugins/ui'

const props = defineProps({
    previousButtonText: {
        type: String,
        default: 'Previous'
    },
    nextButtonText: {
        type: String,
        default: 'Save & Next'
    },
})

const { loading } = useConfig()
</script>