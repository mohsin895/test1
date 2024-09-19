<template>
    <div class="select-none ">
        <ItemComponent
            v-for="(item, index) in menus"
            :key="index"
            :item="item"
        />
    </div>
</template>

<script setup>
    import useAside from '@/composable/useAside.js'
    import ItemComponent from '@/Components/leftAside/ItemComponent.vue'

    const { menus } = useAside()
</script>