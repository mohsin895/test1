<template>
    <button
        :class="twMerge(`${buttonTheme.base} ${buttonTheme[theme]}`, $attrs.class)">
        <PhCircleNotch v-if="loading" class="animate-spin" />
        <slot />
    </button>
</template>

<script setup>
import { twMerge } from 'tailwind-merge'
import { PhCircleNotch } from '@phosphor-icons/vue'
import { buttonTheme } from '../theme';

defineProps({
    loading: {
        type: Boolean,
        default: false
    },
    theme: {
        type: String,
        default: 'primary',
    }
})


</script>