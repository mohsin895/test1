<template>
    <div class="group">
        <div 
            @click="!isEmpty(item.child) && handleDropdown(item.id)"
            class="py-2 flex items-center justify-between cursor-pointer gap-2 border-b group-hover:border-primary bg-white relative z-20"
            :class="(!isEmpty(item.child) && menuId == item.id) || route().current(item.to)  ? 'border-primary' : 'border-black/5'"
        >
            <CustomLink :item="item" />
            <PhCaretDown
                v-if="!isEmpty(item.child)"
                size="16"
                class="group-hover:text-primary duration-300"
                :class="menuId == item.id || route().current(item.to)  ? 'rotate-180 text-primary' : ''"
            />
        </div>
        
        <DropdownComponent 
            v-if="!isEmpty(item.child) && (menuId == item.id || route().current(item.to) )"
            :child="item.child"
        />
    </div>
</template>

<script setup>
    import { PhCaretDown } from '@phosphor-icons/vue'
    import { isEmpty } from 'lodash'
    import DropdownComponent from '@/Components/leftAside/DropdownComponent.vue'
    import CustomLink from '@/Components/leftAside/CustomLink.vue'
    import useAside from '@/composable/useAside.js'

    const { menuId, handleDropdown } = useAside()

    defineProps({
        item: Object
    })
</script>