<template>
    <!-- for mobile -->
    <button 
        class="ml-auto"
        @click="toggleSearchBox = !toggleSearchBox"
    >
        <Icon 
            name="PhMagnifyingGlass" 
            size="25" 
            class="text-white inline-block md:hidden"
        />
    </button>
    <!-- for mobile /-->

    <Transition>
        <div 
            v-if="toggleSearchBox"
            class="md:rounded backdrop-blur-lg bg-white/20 shadow border-b md:border-none border-white/40 md:bg-transparent w-full md:w-auto fixed md:static lg:p-0 p-4 md:p-0 left-0 top-0 z-20 md:block flex gap-4"
        >
            <Input.Primary
                wrapperClass="bg-white/10 border border-white/30 lg:w-[400px] md:w-[300px] w-full rounded"
                iconSource="phosphor"
                icon="PhMagnifyingGlass"
                iconPosition="right"
                iconSize="30"
                iconColor="white"
                placeholder="Enter your query..."
                class="text-white"
                inputClass="placeholder:text-white/30"
            />
            <button 
                class="text-red-500 block md:hidden"
                @click="toggleSearchBox = !toggleSearchBox"
            >
                <Icon name="PhX" size="20" weight="bold" />
            </button>
        </div>
    </Transition>
</template>

<script setup>
    import { Input } from '@/plugins/form'
    import { Icon } from '@/plugins/ui'
    import { ref } from 'vue'
    import { resizeObserver } from '@/helper'

    const toggleSearchBox = ref(false)
    resizeObserver(document.body, () => {
        if(document.body.offsetWidth >= 768){
            toggleSearchBox.value = true
            return
        }
        toggleSearchBox.value = false
    })
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