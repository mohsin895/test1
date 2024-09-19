<template>
    <div 
        class="relative w-fit"
    >
        <img 
            class="block w-full object-cover object-center"
            :class="thumbnail && 'border-4 border-white rounded shadow'"
            v-bind="$attrs"
            @load="isLoading = false"
            :src="src || fallBack"
            loading="lazy"
        />
        <Loader
            :loading="isLoading"
            class="absolute top-1/2 left-1/2 -mt-3 -ml-3 text-3xl drop-shadow-lg shadow-primary text-primary"
        />
    </div>
</template>

<script setup>
    import fallBackImg from '@/assets/image-not-found.webp'
    import { ref } from 'vue'
    import { Loader } from '@/plugins/ui'

    defineOptions({
        inheritAttrs: false
    })
    defineProps({
        src: {
            type: String
        },
        fallBack: {
            type: String,
            default: fallBackImg
        },
        thumbnail: {
            type: Boolean,
            default: false
        }
    })

    const isLoading = ref(true)
</script>