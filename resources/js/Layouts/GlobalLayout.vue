<template>
    <slot></slot>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { watchEffect } from 'vue';
import { toast } from 'vue3-toastify'

const props = defineProps({
    isUserPanel: {
        type: Boolean,
        default: false,
    }
})


let timeout;
watchEffect(() => {
    const props = usePage().props;
    clearTimeout(timeout)
    timeout = setTimeout(() => {
        if(props.flash.message){
            toast.success(props.flash.message, {
                autoClose: 2000,
            })
        }
        if(props.flash.error){
            toast.error(props.flash.error, {
                autoClose: 2000,
            })
        }
    }, 100)
}, {deep: true})

</script>