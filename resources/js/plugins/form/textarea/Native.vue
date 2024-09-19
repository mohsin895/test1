<template>
    <textarea
        :id="uid"
        v-bind="$attrs"
        v-model="modelValue"
        class="w-full outline-none"
    ></textarea>
</template>

<script setup>
    /*global defineModel */
    const modelValue = defineModel()
</script>