<template>
    <tbody v-if="data.length">
        <tr
            v-for="(item, index) in data"
            :key="index"
            class="text-sm text-center items-center justify-center hover:bg-gray-50"
            :class="trClass"
        >
            <template
                v-for="(cItem, cIndex) in propsData.config"
                :key="cIndex"
            >
                <td 
                    class="px-3 py-2 border-b "
                    :class="cItem?.tdClass"
                >
                    <slot name="td" :item="item" :key="cItem?.key">
                        {{ get(item, cItem?.key) || item.default }}
                    </slot>
                    {{ cItem?.cb && cItem?.cb({item, index}) }}
                </td>
            </template>
            <td v-if="$slots.serial" class="px-3 py-2 border-b ">
                <div class="flex gap-2 items-center">
                    <slot name="serial" :item="item"></slot>
                </div>
            </td>
            <td v-if="$slots.name" class="px-3 py-2 border-b ">
                <div class="flex gap-2 items-center">
                    <slot name="name" :item="item"></slot>
                </div>
            </td>

            <td v-if="$slots.action" class="px-3 py-2 border-b ">
                <div class="flex gap-2 ">
                    <slot name="action" :item="item"></slot>
                </div>
            </td>
            <td v-if="$slots.details" class="px-3 py-2 border-b ">
                <div class="flex gap-2 items-center">
                    <slot name="details" :item="item"></slot>
                </div>
            </td>
        </tr>
    </tbody>
</template>

<script setup>
    import { inject } from 'vue'
    import { get } from 'lodash'
    import { data } from '../useTable.js'
   
    const propsData = inject('propsData')
</script>