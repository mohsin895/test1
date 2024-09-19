<template>
    <select
        :id="uid"
        v-bind="$attrs"
        v-model="_localModelValue"
        class="w-full block outline-none bg-transparent"
    >
        <option :value="null">{{ defaultOption }}</option>
        <option
            class="bg-white text-black/60"
            v-for="(item, index) in options"
            :key="index"
            :value="item[itemKey]"
        >
            {{ item[itemValue] }}
        </option>
    </select>
</template>

<script setup>    
    defineProps({
        options: Array,
        itemValue: {
            type: String,
            default: 'title'
        },
        itemKey: {
            type: String,
            default: 'id'
        },
        inputClass: String,
        defaultOption: {
            type: String,
            default: 'Select'
        },
       
        
    })

    defineOptions({
        name: 'NativeSelect',
        inheritAttrs: false
    })
    
    /*global defineModel*/
    const _localModelValue = defineModel()
</script>