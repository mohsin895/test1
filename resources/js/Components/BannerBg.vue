<template>
    <div 
        class="absolute top-0 left-0 inset-0 bg-gradient-to-r from-primary to-sky-500 pointer-events-none"
        :style="`height: ${height}px`"
    >
        <img 
            :src="bannerBg"
            class="absolute bottom-0 w-full object-contain opacity-20"
        />
        <span class="absolute top-0 left-0 w-full h-full bg-black/30"></span>
    </div>
</template>

<script setup>
    import bannerBg from '@/assets/banner-bg.webp'
    defineProps({
        height: {
            type: Number,
            default: 150,
        }
    })
</script>