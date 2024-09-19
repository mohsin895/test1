<template>
    <div
        class="relative flex flex-col border rounded-xl bg-white bg-clip-border text-gray-700 shadow-md"
    >
        <div class="flex justify-between items-center p-4">
            <div class="text-left">
                <p
                    class="text-blue-gray-600 block font-sans text-sm font-normal leading-normal antialiased"
                >
                    {{ label }}
                </p>
                <h4
                    class="text-blue-gray-900 block font-sans text-xl font-semibold leading-snug tracking-normal antialiased"
                >
                    <slot></slot>
                </h4>
            </div>
            <div
                :class="twMerge(`grid h-12 w-12 place-items-center overflow-hidden rounded-xl bg-gradient-to-tr bg-clip-border text-white shadow-lg shadow-blue-500/40`, classes)"
            >
                <Icon :name="icon" size="28" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { Icon } from "@/plugins/ui";
import { twMerge } from 'tailwind-merge';
const props = defineProps({
    icon: {
        type: String,
        default: "PhEye",
    },
    label: {
        type: String,
        default: "",
    },
    classes: {
        type: String,
        default: "",
    },
});
</script>
