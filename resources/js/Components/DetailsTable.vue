<template>

<div class="relative overflow-x-auto bg-white p-6 rounded shadow-lg min-w-[600px]">
    <h1 class="font-bold text-lg mb-4">
        {{ title }}
    </h1>
    <table class="w-full text-sm text-left text-gray-500">
        <tbody>
            <tr
                v-for="(item, index) in config"
                :key="index"
                :class="index % 2 ? 'bg-gray-50' : ''"
            >
                <th scope="row" class="px-6 py-4 font-bold text-gray-900 border whitespace-nowrap w-5">
                    {{ item.title }}
                </th>
                <td class="px-6 py-4 border">
                    <template v-if="item.downloadable">
                        <Button.Success 
                            :to="get(data, item.key)" 
                            :download="get(data, item.key)"
                        >
                            Download
                        </Button.Success>
                    </template>
                    <template v-if="item.viewable">
                        <Button.Primary
                            :to="get(data, item.key)"
                            target="_blank"
                            class="!px-2"
                    >
                        <Icon name="PhEye" size="20" weight="bold" />
                    </Button.Primary>
                    </template>

                    <template v-if="!item.viewable && !item.downloadable">
                        <div class="flex items-center gap-4">
                            {{ get(data, item.key) }}

                            <template v-if="get(data, item.standard)">
                                <div class="font-bold text-green-600">
                                    Standard: {{ get(data, item.standard) }}
                                </div>
                            </template>
                        </div>
                    </template>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="mt-4 flex justify-end">
        <Button.Danger @click="$emit('close')">
            Close
        </Button.Danger>
    </div>
</div>

</template>

<script setup>
import { get } from 'lodash'
import { Button, Icon } from '@/plugins/ui'

const props = defineProps({
    data: {
        type: Object,
        default: {}
    },
    config: {
        type: Array,
        default: []
    },
    title: {
        type: String,
        default: 'Details Information'
    }
})
</script>