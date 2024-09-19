<template>
    <Button.Native
        :loading="loading"
        :to="to"
        :class="
            outline 
                ? 'bg-white hover:bg-primary text-primary hover:text-white border-primary hover:border-white'
                : 'bg-primary border-primary text-white hover:bg-white hover:text-primary'
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