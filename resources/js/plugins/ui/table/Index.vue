<template>
    <div class="w-full">
        <TableHeader :search="search">
            <slot name="tableHeader"></slot>
        </TableHeader>

        <div class="overflow-auto relative">
            <table class="w-full">
                <THead
                    :hasActionSlot="$slots.action"
                    :theadClass="theadClass"
                />

                <TBody>
                    <template #td="{ item, key }">
                        <slot name="td" :item="item" :key="key"></slot>
                    </template>
                   
                    <template v-if="$slots.action" #action="{ item, key }">
                        <slot name="action" :item="item" :key="key"></slot>
                    </template>
                </TBody>
            </table>
        </div>

        <div v-if="!data.length" class="bg-gray-100">
            <slot name="alert">
                <h3 class="py-2 px-4 text-sm text-center">No data found</h3>
            </slot>
        </div>

        <TableFooter class="mt-2">
            <slot name="tableFooter"></slot>
        </TableFooter>
    </div>
</template>

<script setup>
import TableHeader from './fragments/TableHeader.vue'
import TableFooter from './fragments/TableFooter.vue'
import TBody from './fragments/TBody.vue'
import THead from './fragments/THead.vue'
import { provide, watchEffect } from 'vue'
import { data, cloneData, options, showSearchInput } from './useTable.js'
import { cloneDeep } from 'lodash'


const props = defineProps({
    config: {
        type: Array,
        default: () => []
    },
    searchKeys: {
        type: Array,
        default: () => []
    },
    theadClass: {
        type: String,
        default: ''
    },
    trClass: {
        type: String,
        default: ''
    },
    data: {
        type: Array
    },
    search: {
        type: Boolean,
        default: true
    },
    showSearch: {
        type: Boolean,
        default: true
    },
})

showSearchInput.value = props.showSearch

provide('propsData', props)

watchEffect(() => {
    data.value = props.data
    cloneData.value = cloneDeep(props.data)
    options.value.keys = props.searchKeys
})
</script>