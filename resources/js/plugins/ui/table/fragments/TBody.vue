<template>
    <tbody v-if="data.length">
        <tr
            v-for="(item, index) in data"
            :key="index"
            class="text-sm hover:bg-gray-50"
            :class="trClass"
        >
            <template
                v-for="(cItem, cIndex) in propsData.config"
                :key="cIndex"
            >
                <td 
                    class="px-3 py-2 border-b"
                    :class="cItem?.tdClass"
                >
                    <slot name="td" :item="item" :key="cItem?.key">
                        {{ get(item, cItem?.key) || item.default }}
                    </slot>
                    {{ cItem?.cb && cItem?.cb({item, index}) }}
                </td>
            </template>
            

            <td v-if="$slots.action" class="px-3 py-2 border-b">
                <div class="flex gap-2 items-center">
                    <slot name="action" :item="item"></slot>
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