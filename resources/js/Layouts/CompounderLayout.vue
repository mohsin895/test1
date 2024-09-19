<template>
    <Auth :title="title" aside="compounder">
        <slot></slot>
    </Auth>
</template>

<script setup>
    import Auth from './Auth.vue'
    import { usePage } from '@inertiajs/vue3'
    import { onMounted } from 'vue'
    import useAside from '@/composable/useAside'
    import { get } from 'lodash'

    const props = defineProps({
        title: {
            type: String,
            default: null
        }
    })


</script>
