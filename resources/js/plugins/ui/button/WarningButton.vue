<template>
    <Button.Native
        :loading="loading"
        :to="to"
        :class="
            outline 
                ? 'bg-white hover:bg-yellow-500 text-yellow-500 hover:text-white border-yellow-500 hover:border-white'
                : 'bg-yellow-500 border-yellow-500 text-white hover:bg-white hover:text-yellow-500'
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