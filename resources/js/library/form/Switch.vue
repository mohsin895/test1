<template>
    <div class="py-2 flex items-center flex-col justify-center">
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" v-model="modelValue" class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
            <slot name="label">
                <span v-if="label" class="ml-3 text-sm font-medium text-current">
                    {{ label }}
                </span>
            </slot>
        </label>
        <slot name="error">
            <span v-if="error" :class="twMerge('text-red-500 text-sm mt-2', errorClass)">
                {{ error }}
            </span>
        </slot>
    </div>
</template>

<script setup>
import { twMerge } from 'tailwind-merge'
const props = defineProps({
    label: {
        type: String,
        default: null,
    },
    errorClass: {
        type: String,
        default: null
    },
    error: {
        type: String,
        default: null
    }
})

const modelValue = defineModel()

</script>