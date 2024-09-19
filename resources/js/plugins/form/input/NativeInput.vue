<template>
    <input
        :id="uid"
        v-bind="$attrs"
        v-model="modelValue"
        class="w-full outline-none"
    />
</template>

<script setup>
    /*global defineModel */
    const modelValue = defineModel()
</script>