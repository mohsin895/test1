<script setup>
const modalValue = defineModel()
</script>

<template>
    <input
        type="checkbox"
        v-model="modalValue"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
    />
</template>
