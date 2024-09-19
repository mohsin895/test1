<template>
    <Auth :title="title" aside="doctor">
        <slot></slot>
    </Auth>
</template>

<script setup>
    import Auth from './Auth.vue'
    import { usePage } from '@inertiajs/vue3'
    import { onMounted } from 'vue'
    import useAside from '@/composable/useAside'
    import { get } from 'lodash'

    const props = defineProps({
        title: {
            type: String,
            default: null
        }
    })

    // const { menus, doctorMenu } = useAside();

    // onMounted(() => {
    //     const id = get(usePage().props, 'auth.user.id')
    //     if (id) {
    //         let item = {
    //             iconName: "PhGauge",
    //             to: route('doctor.configurations', id),
    //             full_route: true,
    //             title: "Configurations",
    //         }
    //         doctorMenu.push(item)
    //         console.log(doctorMenu);
    //     }
    // })

</script>
