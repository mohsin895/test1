<template>
    <Link
        v-if="href"
        class="px-4 py-1 whitespace-nowrap rounded border shadow hover:shadow-lg active:scale-95 duration-300 select-none inline-flex items-center justify-center gap-2"
        :class="loading ? 'pointer-events-none opacity-70' : ''"
        :href="href"
    >
        <slot></slot>
    </Link>
    <a
        v-else-if="to"
        class="px-4 py-1 whitespace-nowrap rounded border shadow hover:shadow-lg active:scale-95 duration-300 select-none inline-flex items-center justify-center gap-2"
        :class="loading ? 'pointer-events-none opacity-70' : ''"
        :href="to"
    >
        <slot></slot>
    </a>
    <button 
        v-else
        class="px-4 py-1 whitespace-nowrap rounded border shadow hover:shadow-lg active:scale-95 duration-300 select-none inline-flex items-center justify-center gap-2"
        :class="loading ? 'pointer-events-none opacity-70' : ''"
    >
        <slot></slot>
        
        <Loader 
            :loading="loading"
            class="text-xl"
        />
    </button>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';
    import { Loader } from '@/plugins/ui'
    defineProps({
        loading: {
            type: Boolean,
            default: false,
        },
        to: [Object, String],
        href: {
            type: String,
            default: null
        }
    })
</script>