<template>
    <button
        v-if="!isEmpty(item.child)"
        class="flex items-center gap-2 group-hover:text-primary duration-300 w-full truncate"
        :class="menuId == item.id || route().current(item.to) ? 'text-primary font-semibold' : ''"
        :title="item.title"
    >
        <span class="inline-block w-4 h-4">
            <Icon :name="item.iconName" size="16" />
        </span>
        <span class="block truncate">
            {{ item.title }}
        </span>
    </button>
    <Link 
        v-else
        class="flex items-center gap-2 group-hover:text-primary duration-300 w-full"
        :class="route().current(item.to) ? 'text-primary font-semibold' : ''"
        :href="route(item.to)"
        :title="item.title"
    >
        <span class="inline-block w-4 h-4">
            <Icon :name="item.iconName" size="16" />
        </span>
        <span class="block truncate">
            {{ item.title }}
        </span>
    </Link>
</template>

<script setup>
    import { isEmpty } from 'lodash'
    import useAside from '@/composable/useAside.js'
    import { Link } from '@inertiajs/vue3';
    import { Icon } from '@/plugins/ui';

    const { menuId } = useAside()

    defineProps({
        item: Object
    })
</script>