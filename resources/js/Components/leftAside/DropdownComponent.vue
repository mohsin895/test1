<template>
    <div class="relative py-2">
        <span class="absolute left-2 top-0 h-[calc(100%-25px)] border-r bg-red-500"></span>
        <Link
            v-for="(item, index) in child"
            :key="index"
            class="group/link items-center gap-2 text-sm pl-8 hover:text-primary duration-300 relative py-2 block w-full cursor-pointer"
            :class="route().current(item.to) ? 'text-primary font-semibold' : ''"
            :href="route(item.to)"
            :title="item.title"
        >
            <span 
                class="absolute left-2 bottom-[calc(100%-18px)] h-[100%] z-10 border-l group-hover/link:border-primary duration-300"
                :class="route().current(item.to) ? '!border-l-primary' : 'border-l-transparent'"
            >
            </span>
            <span 
                class="absolute left-2 top-1/2 w-4 border-b group-hover/link:border-primary duration-300"
                :class="{'border-primary': route().current(item.to)}"
            ></span>
            <span class="block truncate">
                {{ item.title }}
            </span>
        </Link>
    </div>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3'
    defineProps({
        child: Array
    })
</script>