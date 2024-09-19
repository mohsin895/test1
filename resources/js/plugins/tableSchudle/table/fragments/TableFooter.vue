<template>
    <div>
        <slot></slot>
        <h3 class="text-right text-sm">
           
        </h3>
    </div>
</template>

<script setup>
    import { inject } from 'vue'

    const { data } = inject('propsData')
</script>