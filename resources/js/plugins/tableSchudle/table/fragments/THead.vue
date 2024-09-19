<template>
    <thead>
        <tr class="bg-gray-100 whitespace-nowrap text-sm" :class="theadClass">
            <th 
                v-for="(item, index) in propsData.config"
                :key="index"
                class="group border-b font-medium text-left px-3 py-2 pr-6 align-middle relative cursor-pointer select-none"
                :class="item?.thClass"
                @click="item?.sortable && sortData(item?.key)"
            >
                {{ item?.title }}
                <button v-if="item?.sortable" class="absolute right-0 opacity-30 group-hover:opacity-80">
                    <Icon name="PhCaretUpDown" size="16" />
                </button>
            </th>
            <th v-if="hasActionSlot" class="text-left border border-white w-10 border-b font-medium px-3 py-2">
              Sl.
            </th>
            <th v-if="hasActionSlot" class="text-left border border-white w-10 border-b font-medium px-3 py-2">
              Name
            </th>
           
            <th v-if="hasActionSlot" class="text-left border min-w-[100px] border-white w-10 border-b font-medium px-3 py-2">
                Action
            </th>
            <th v-if="hasActionSlot" class="text-left w-10 border-b font-medium px-3 py-2">
                Details
            </th>
        </tr>
    </thead>
</template>

<script setup>
    import { inject } from 'vue'
    import { Icon } from '@/plugins/tableSchudle'
    import { sortData } from '../useTable.js'

    defineProps({
        hasActionSlot: Boolean,
        theadClass: String,
    })
    const propsData = inject('propsData')
</script>