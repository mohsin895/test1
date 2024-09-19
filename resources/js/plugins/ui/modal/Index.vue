<template>
    <div
        @mousedown.self="mouseDown=true"
        @mouseup="handleClose"
        @mouseout.self="mouseDown=false"
        class="fixed top-0 left-0 right-0 bottom-0 z-50 bg-black bg-opacity-50 backdrop-blur-md z-100 grid place-content-center transition-all"
        :class="modelValue ? 'opacity-100 visible' : 'opacity-0 invisible'"
    >
        <div
            class="max-h-[90vh] overflow-y-auto mx-4"
            :class="isFull ? 'w-[90vw]' : ''"
        >
            <button
                @click="() => {
                    modelValue = false
                    $emit('close', false)
                }"
                class="bg-red-500 text-white absolute top-4 right-4 p-2 rounded"
            >
                <Icon name="PhX" size="30" />
            </button>
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import { Icon } from "@/plugins/ui";
import { ref } from 'vue'

defineProps({
    isFull: {
        type: Boolean,
        default: false,
    },
});

const modelValue = defineModel();
const mouseDown = ref(false)

const handleClose = () => {
    if (mouseDown.value) {
        modelValue.value = false
    }
    mouseDown.value = false
}

</script>
