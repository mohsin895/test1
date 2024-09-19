<template>
    <Button.Native
        :loading="loading"
        :to="to"
        :class="
            outline 
                ? 'bg-white hover:bg-green-500 text-green-500 hover:text-white border-green-500 hover:border-white'
                : 'bg-green-500 border-green-500 text-white hover:bg-white hover:text-green-500'
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