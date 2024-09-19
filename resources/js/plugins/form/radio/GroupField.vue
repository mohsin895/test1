<template>
    <div class="relative">
        <label 
            v-if="label" 
            class="mb-1 block text-black/80" 
            :class="labelClass"
        >
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        <div 
            class="flex gap-4"
            :class="[vertical && 'flex-col', error && 'bg-red-50 rounded']"
        >
            <slot></slot>
        </div>
        <span v-if="error" class="text-[10px] text-red-500 absolute top-full right-0">
            {{ error }}
        </span>
    </div>
</template>

<script setup>
    import { provide } from 'vue'

    defineOptions({
        inheritAttrs: false,
        name: 'GroupField'
    })

    defineProps({
        vertical: {
            type: Boolean,
            default: false
        },
        label: String,
        labelClass: String,
        error: String,
        required: Boolean,
    })

    /*global defineModel*/
    const modelValue = defineModel()
    const setModelValue = (value) => {
        modelValue.value = value
    }
    provide('modelValue', {
        modelValue,
        setModelValue
    })
</script>