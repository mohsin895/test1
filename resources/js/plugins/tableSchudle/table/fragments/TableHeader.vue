<template>
    <div class="flex justify-between items-center mb-2">
        <div>
            <slot></slot>
        </div>
        <Input.Primary 
            v-if="options.keys?.length && showSearchInput"
            type="search" 
            class="border mb-2 px-2 py-1"
            placeholder="Search..."
            @input="searchData"
        />
    </div>
</template>

<script setup>
    import { inject } from 'vue'
    import { Input } from '@/plugins/form'
    import { searchData, options, showSearchInput } from '../useTable.js'

    const { search } = inject('propsData')
</script>