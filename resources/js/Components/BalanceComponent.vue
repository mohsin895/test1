<template>
    <div v-outsideClick="()=>{balanceToggle=false}" class="relative z-20">
        <button 
            @click="balanceToggle = !balanceToggle"
            class="flex items-center gap-2 py-[6px] text-white group-hover:text-primary"
        >
            Balance
            <Icon name="PhCaretDown" size="16" />
        </button>

        <Transition>
            <div v-if="balanceToggle" class="w-[250px] bg-white shadow-lg rounded absolute top-full right-0 border border-primary/20">
                <span class="w-4 h-4 border-r border-t border-primary/20 block -rotate-45 absolute bottom-full -mb-2 right-4 bg-white"></span>
                <div class="border-b px-4 py-3 text-center font-medium rounded-t flex gap-2 items-center justify-center">
                    Your balance <span class="border-b border-primary text-sm px-1 text-primary">BDT 53705</span>
                </div>
                <div class="p-4 text-center grid gap-4">
                    Send a Deposit Request
                    <Button.Default class="w-fit mx-auto">
                        Deposit
                    </Button.Default>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
    import { ref } from 'vue'
    import { vOutsideClick } from '@/helper'
    import { Icon, Button } from '@/plugins/ui'
    
    const balanceToggle = ref(false)
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