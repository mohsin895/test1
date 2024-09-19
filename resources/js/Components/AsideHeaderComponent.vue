<template>
    <div class="flex gap-6 justify-between">
        <LogoComponent />
        <button @click="toggleFullScreen">
            <PhList :size="24" color="#0a0a0a" />
        </button>
    </div>
</template>

<script setup>
import { PhList } from '@phosphor-icons/vue'
import LogoComponent from '@/Components/LogoComponent.vue'
import { toggleFullScreen } from '@/helper'

</script>