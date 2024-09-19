<template>
    <PhCircleNotch
        v-if="loading"
        class="animate-spin"
    />
</template>

<script setup>
    import { PhCircleNotch } from '@phosphor-icons/vue'

    defineProps({
        loading: {
            type: Boolean,
            default: false
        }
    })
</script>