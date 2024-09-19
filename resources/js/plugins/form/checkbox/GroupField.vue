<template>
    <div 
        class="flex gap-2"
        :class="vertical ? 'flex-col' : ''"
    >
        <slot></slot>
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
        }
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