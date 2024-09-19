<template>
    <div class="py-4 px-4 shadow-md border inline-flex flex-col items-center gap-3 rounded-xl">
        <div
            :class="twMerge(`grid h-16 w-16 place-items-center overflow-hidden rounded-full bg-gradient-to-tr from-blue-600 to-blue-400 bg-clip-border text-white shadow-lg shadow-blue-500/40`, classes)"
        >
            <Icon :name="icon" size="30" />
        </div>
        <div class="font-semibold text-center">
            {{ label }}
        </div>
    </div>
</template>

<script setup>
    import { Icon } from '@/plugins/ui';
    import { twMerge } from 'tailwind-merge';
    const props = defineProps({
        icon: {
            type: String,
            default: 'PhEye'
        },
        classes: {
            type: String,
            default: ''
        },
        label: {
            type: String,
            default: ''
        }
    })
</script>
