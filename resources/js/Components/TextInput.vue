<template>
    <input
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 border rounded-md shadow-sm p-2 focus:outline-none"
        v-model="modalValue"
        ref="input"
    />
</template>

<script setup>
import { onMounted, ref } from 'vue';

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

const modalValue = defineModel()

</script>