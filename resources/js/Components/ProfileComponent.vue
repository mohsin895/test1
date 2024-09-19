<template>
    <div v-outsideClick="()=>{toggleDropdown=false}" class="group relative z-10">
        <button 
            class="w-10 h-10 rounded-full border-2 border-white group-hover:border-primary/80 shadow duration-300"
            @click="toggleDropdown = !toggleDropdown"
        >
            <Img 
                :src="get($page.props.auth.user, 'media.path')"
                :fallBack="defaultAgent"
                alt="Profile Image"
                class="rounded-full bg-white aspect-square"
            />
        </button>

        <Transition>
            <div v-if="toggleDropdown" class="w-[250px] bg-white shadow-lg rounded absolute top-full right-0 mt-1 border border-primary/20">
                <span class="w-4 h-4 border-r border-t border-primary/20 block -rotate-45 absolute bottom-full -mb-2 right-3 bg-white"></span>
                <!-- <Link :href="route('app.profile.index')" class="border-b border-primary/20 px-4 py-3 text-center font-medium flex gap-2 items-center hover:text-primary hover:bg-primary/10 duration-300 rounded-t">
                    <Icon name="PhUser" :size="16" weight="bold" />
                    My Profile
                </Link> -->
                <button 
                    @click="logout"
                    class="border-b border-primary/20 px-4 py-3 text-center font-medium flex w-full gap-2 items-center hover:text-primary hover:bg-primary/10 duration-300 rounded-b"
                >
                    <Icon name="PhSignOut" :size="16" weight="bold" />
                    Logout
                </button>
            </div>
        </Transition>
    </div>
</template>

<script setup>
    import { Link, router } from '@inertiajs/vue3'
    import { ref } from 'vue'
    import { Icon, Img } from '@/plugins/ui'
    import { vOutsideClick } from '@/helper'
    import defaultAgent from '@/assets/default-agent.webp'
    import { get } from 'lodash'
    const toggleDropdown = ref(false)

    const logout = () => {
        router.post(route('logout'))
    }

</script>

<style scoped>
    .v-enter-active,
    .v-leave-active {
        transition: opacity 0.5s ease;
    }

    .v-enter-from,
    .v-leave-to {
        opacity: 0;
    }
</style>