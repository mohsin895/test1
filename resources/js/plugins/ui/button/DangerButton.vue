<template>
    <Button.Native
        :loading="loading"
        :to="to"
        :class="
            outline 
                ? 'bg-white hover:bg-red-500 text-red-500 hover:text-white border-red-500 hover:border-white'
                : 'bg-red-500 border-red-500 text-white hover:bg-white hover:text-red-500'
            "
    >
        <slot></slot>
    </Button.Native>
</template>

<script setup>
    import { Button } from '@/plugins/ui'
    defineProps({
        loading: {
            type: Boolean,
            default: false,
        },
        outline: Boolean,
        to: [Object, String]
    })
</script>