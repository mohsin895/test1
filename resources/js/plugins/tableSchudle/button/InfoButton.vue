<template>
    <Button.Native
        :loading="loading"
        :to="to"
        :class="
            outline 
                ? ' bg-slate-700 hover:bg-white  text-white hover:text-slate-700 border-slate-700 hover:border-white'
                : 'bg-white border-slate-700  text-slate-700 hover:bg-slate-700 hover:text-white'
            "
    >
        <slot></slot>
    </Button.Native>
</template>

<script setup>
    import { Button } from '@/plugins/tableSchudle'
    defineProps({
        loading: {
            type: Boolean,
            default: false,
        },
        outline: Boolean,
        to: [Object, String]
    });
</script>