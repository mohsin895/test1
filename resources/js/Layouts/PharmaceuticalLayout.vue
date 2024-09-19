<template>
    <Auth :title="title" aside="pharmaceutical">
        <slot></slot>
    </Auth>
</template>

<script setup>
import Auth from "./Auth.vue";
import useAside from "@/composable/useAside";

const props = defineProps({
    title: {
        type: String,
        default: null,
    },
});

const { activeAside } = useAside();
</script>
