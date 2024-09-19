<template>
    <div class="flex justify-between gap-6 items-center">
        <div 
            class="flex items-center"
            :class="isFullScreen && 'gap-6'"
        >
            <button 
                v-if="isFullScreen && !hideSidebar"
                @click="toggleFullScreen"
            >
                <PhList :size="26" color="#fff" />
            </button>
            <LogoComponent 
                v-if="isFullScreen"
                class="sm:w-[200px] w-[200px]"
                type="light"
            />
            <h1 v-if="isAdmin" class="text-3xl font-bold text-white uppercase">
                pgcb
            </h1>
            <h1 v-else class="text-lg font-bold text-white uppercase">
                {{ get($page.props, 'current_project.project.project_name') }}
            </h1>
            <!-- <SearchComponent /> -->
        </div>

        <div class="flex items-center gap-4">
            <!-- <BalanceComponent
                class="md:block hidden"
            /> -->
            <ProfileComponent />
        </div>
    </div>
</template>

<script setup>
import { PhList } from '@phosphor-icons/vue'
import ProfileComponent from '@/Components/ProfileComponent.vue'
import SearchComponent from '@/Components/SearchComponent.vue'
import LogoComponent from '@/Components/LogoComponent.vue'
import { get } from 'lodash'
import { isFullScreen, toggleFullScreen } from '@/helper'
import useAside from '@/composable/useAside'

const { isAdmin } = useAside()

const props = defineProps({
    hideSidebar: {
        type: Boolean,
        default: false
    }
})

</script>