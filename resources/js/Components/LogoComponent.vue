<template>
    <Link :href="dashboardRoute" class="flex items-center">
        <Img src="/frontend/home-page-img/logo-no-background.png" alt="Logo" class="!object-contain" />
    </Link>
</template>

<script setup>
    import { Img } from '@/plugins/ui'
    import LogoImgLight from '@/assets/logo-light.png'
    import { Link } from '@inertiajs/vue3'
    import useAside from '@/composable/useAside.js'
    import { computed } from 'vue'
    import { get } from 'lodash'
    // dashboardLinks
    const props = defineProps({
        type: {
            type: String,
            default: 'dark'
        }
    })

    const { activeAside, dashboardLinks } = useAside()

    const dashboardRoute = computed(() => {
        let link = '/'
        try {
            link = route(get(dashboardLinks, `${activeAside.value}`))
        } catch (error) {}
        return link
    })
</script>