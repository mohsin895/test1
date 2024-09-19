<template>
    <Combobox :modelValue="modelValue" @update:modelValue="updateModelValue">
        <div class="relative">
            <h1 v-if="label" class="mb-1" :class="labelClass">
                {{ label }}
            </h1>
            <div 
                class="relative w-full cursor-default overflow-hidden text-left focus:outline-none sm:text-sm"
                :class="disabled ? 'opacity-60 bg-gray-100 pointer-events-none select-none' : ''"
            >

                <ComboboxButton 
                    v-if="icon && iconPosition == 'left'" 
                    class="absolute inset-y-0 left-0 flex items-center pl-2"
                    @click="$emit('iconClick')"
                >
                    <Icon class="text-gray-400" :name="icon" :class="iconClass" :size="iconSize" aria-hidden="true" />
                </ComboboxButton>
                <ComboboxInput
                    class="w-full py-2 text-sm leading-5 text-gray-900 border border-black/10 outline-none rounded focus:border-black/30"
                    :class="[
                        icon && iconPosition 
                        ? iconPosition == 'left' ? 'pr-3 pl-7' : 'pl-3 pr-7' 
                        : 'px-3'
                    ]"
                    :displayValue="getDisplayValue" 
                    @change="(e) => {
                        query = e.target.value
                    }"
                    :title="getDisplayValue()"
                    :placeholder="placeholder" 
                    v-bind="$attrs"
                />
                <ComboboxButton 
                    v-if="icon && iconPosition == 'right'" 
                    class="absolute inset-y-0 right-0 flex items-center pr-2"
                    @click="$emit('iconClick')"
                >
                    <Icon class="text-gray-400" :name="icon" :class="iconClass" :size="iconSize" aria-hidden="true" />
                </ComboboxButton>
                <div 
                    class="absolute inset-y-0 right-0 flex items-center pr-2"
                    title="Loading Auto-Complete"
                >
                    <Loader
                        :loading="loading"
                        class="text-primary"
                        size="20"
                    />
                </div>
            </div>

            <TransitionRoot leave="transition ease-in duration-200 relative" leaveFrom="opacity-100" leaveTo="opacity-0"
                @after-leave="query = ''">
                <ComboboxOptions v-if="filteredList"
                    class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md z-20 bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                    <div v-if="filteredList.length === 0 && query !== ''"
                        class="relative cursor-default select-none py-2 px-4 text-gray-700">
                        Nothing found.
                    </div>

                    <ComboboxOption v-for="item in filteredList" as="template" :key="item.id" :value="item"
                        v-slot="{ selected, active }">
                        <li 
                            class="relative cursor-default select-none py-2 pl-10 pr-4" 
                            :class="{
                                'bg-primary text-white': active,
                                'text-gray-900': !active,
                            }"
                            :title="item.item[itemValue]"
                        >
                            <div
                                class="block truncate" 
                                :class="{ 'font-medium': selected, 'font-normal': !selected }"
                                v-html="listValueFormatter(item.item)"
                            ></div>
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3"
                            >
                                <Icon 
                                    name="PhCheck" 
                                    size="20" 
                                    aria-hidden="true"
                                    :class="active ? 'text-white' : 'text-primary'"
                                />
                            </span>
                        </li>
                    </ComboboxOption>
                </ComboboxOptions>
            </TransitionRoot>
        </div>
    </Combobox>
</template>
  
<script setup>
import { onMounted, ref, watch } from 'vue'
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from '@headlessui/vue'
import { Icon, Loader } from '@/plugins/ui'
import Fuse from 'fuse.js'

defineOptions({
    inheritAttrs: false,
    name: 'InputAutoComplete',
})

/*global defineModel */
const modelValue = defineModel()

const props = defineProps({
    itemKey: {
        type: String,
        default: 'id',
    },
    itemValue: {
        type: String,
        default: 'title',
    },
    defaultOption: {
        type: String,
        default: 'Select',
    },
    suggestions: {
        type: Array,
        default: () => []
    },
    placeholder: {
        type: String,
        default: 'Enter your data.'
    },
    label: String,
    icon: Object,
    iconPosition: {
        type: String,
        default: 'left'
    },
    iconSize: {
        type: [Number, String],
        default: '25'
    },
    iconColor: {
        type: String,
        default: 'inherit'
    },
    iconClass: {
        type: String
    },
    labelClass: {
        type: String,
    },
    listValueFormatter: {
        type: Function,
        default: function(item) {
            return item[this.itemValue]
        }
    },
    searchKeys: {
        type: Array,
        default: function() {
            return [this.itemValue]
        }
    },
})


let timeoutId = null
const query = ref('')
const filteredList = ref('')
const loading = ref(false)

const updateModelValue = ({item}) => {
    modelValue.value = `${item[props.itemValue]} (${item[props.itemKey]})`
}
const getDisplayValue = () => {
    return modelValue.value
}

onMounted(() => {
    loading.value = !props.suggestions.length
})

watch([props, query], () => {
    loading.value = !props.suggestions.length
    if (query.value.length < 3 || !props.suggestions.length) {
        filteredList.value = []
        return
    }
    getFilteredData()
}, { deep: true })

const getFilteredData = () => {
    clearTimeout(timeoutId)
    timeoutId = setTimeout(() => {
        const fuseOptions = {
            isCaseSensitive: false,
            includeScore: false,
            findAllMatches: false,
            threshold: 0.2,
            keys: props.searchKeys
        }
        let _query = query.value.toLowerCase().replace(/\s+/g, '')
        const fuse = new Fuse(props.suggestions, fuseOptions);
        filteredList.value = fuse.search(_query)
    }, 800)
}
</script>  