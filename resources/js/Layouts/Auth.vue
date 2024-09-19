<template>
    <Head :title="title" />
    <GlobalLayout>
        <main class="flex bg-slate-100 h-full overflow-y-hidden relative">
            <div v-if="width < 768 && !isFullScreen" class="w-full h-full bg-black/30 fixed z-10"></div>
            <div
                class="w-[var(--asideWidth)] px-5 py-10 bg-white shadow h-full duration-300 fixed overflow-y-auto asideScrollbar top-0 z-10"
                :class="isFullScreen ? 'left-[var(--asideWidthM)]' : 'left-0'"
                ref="aside"
            >
                <AsideHeaderComponent />
                <LeftAside class="pt-10" />
            </div>
            <div
                class="duration-300 bg-slate-200 relative ml-auto h-full"
                :class="isFullScreen ? 'w-full' : 'md:w-[calc(100%-var(--asideWidth))] w-full'"
            >
                <BannerBg :height="title ? 200 : 150" />
                <div class=" p-2 md:p-6  relative h-full overflow-y-auto">
                    <TopNavigationComponent
                        class="mb-6"
                        :hideSidebar="hideSidebar"
                    />
                    <h2 v-if="title" class="font-bold text-xl mb-5 text-white">
                        {{ title }}
                    </h2>
                    <div class="bg-white rounded px-4 py-4 md:px-6 md:py-6 shadow z-10">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </main>
    </GlobalLayout>
</template>

<script setup>
    import AsideHeaderComponent from '@/Components/AsideHeaderComponent.vue'
    import TopNavigationComponent from '@/Components/TopNavigationComponent.vue'
    import LeftAside from "@/Components/leftAside/Index.vue"
    import BannerBg from '@/Components/BannerBg.vue'
    import { isFullScreen } from '@/helper'
    import { watch, onMounted, ref } from 'vue'
    import { usePage, Head } from '@inertiajs/vue3'
    import { toast } from 'vue3-toastify'
    import { useWindowSize, onClickOutside } from '@vueuse/core'
    import GlobalLayout from './GlobalLayout.vue'
    import useAside from '@/composable/useAside'

    const props = defineProps({
        title: {
            type: String,
            default: null
        },
        aside: {
            type: String,
            default: 'admin',
        },
        hideSidebar: {
            type: Boolean,
            default: false
        }
    })

    let { width } = useWindowSize()
    const { activeAside } = useAside()

    activeAside.value = props.aside

    const handleFullScreen = (w) => {
        if(!props.hideSidebar) {
            if (width.value < 768) {
                isFullScreen.value = true
            } else if(isFullScreen.value == true) {
                isFullScreen.value = false
            }
        }
    }

    watch(width, (w) => handleFullScreen()) 
    onMounted(() => {
        if(props.hideSidebar) {
            isFullScreen.value = props.hideSidebar
        } else {
            handleFullScreen()
        }
    })

    const aside = ref()

    onClickOutside(aside, () => {
        if (width.value < 768) {
            isFullScreen.value = true
        }
    })

    let timeout;
    watch(usePage().props, (props) => {
        clearTimeout(timeout)
        timeout = setTimeout(() => {
            if(props.flash.message){
                toast.success(props.flash.message, {
                    autoClose: 2000,
                })
            }
            if(props.flash.error){
                toast.error(props.flash.error, {
                    autoClose: 2000,
                })
            }
        }, 100)
    }, {deep: true})
    
</script>

<style scoped>
.asideScrollbar::-webkit-scrollbar {
    width: 5px;
}
</style>