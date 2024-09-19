<template>
    <div class="py-2">
        <slot name="label">
            <span v-if="label" :class="twMerge(`px-1 pb-2 w-full block text-black`, labelClass)">
                {{ label }}
            </span>
        </slot>
        <select 
            name=""
            :class="twMerge(`text-md block px-3 ${type=='file' ? 'py-[5px]' : 'py-2'} rounded-lg w-full 
                border border-gray-300 placeholder-gray-600 
                focus:placeholder-gray-500 focus:border-gray-600
                transition duration-200
                focus:outline-none`, inputClass)
            "
            v-model="modelValue"
        >
            <option v-for="(item, index) in options" :key="index" :value="item[value]">{{ item[text] }}</option>
        </select>
        <slot name="error">
            <span v-if="error" :class="twMerge('text-red-500 text-sm mt-2', errorClass)">
                {{ error }}
            </span>
        </slot>
    </div>
</template>

<script setup>
import { twMerge } from 'tailwind-merge';
const props = defineProps({
    label: {
        type: String,
        default: null
    },
    options: {
        type: Array,
        default: []
    },
    text: {
        type: String,
        default: null
    },
    value: {
        type: String,
        default: null
    },
})

const modelValue = defineModel()

</script>