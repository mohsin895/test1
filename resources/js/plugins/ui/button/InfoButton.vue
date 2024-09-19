<template>
    <Button.Native
        :loading="loading"
        :to="to"
        :class="
            outline 
                ? 'bg-white hover:bg-slate-700 text-slate-700 hover:text-white border-slate-700 hover:border-white'
                : 'bg-slate-700 border-slate-700 text-white hover:bg-white hover:text-slate-700'
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