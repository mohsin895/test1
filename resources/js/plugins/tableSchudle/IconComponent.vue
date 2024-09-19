<template>
    <span
        v-if="name && iconComponent"
        :title="title"
    >
        <component
            :is="iconComponent"
            v-bind="$attrs"
        />
    </span>
</template>

<script setup>
    import { shallowRef, watch } from 'vue'
    defineOptions({
        name: 'IconWrapper',
        inheritAttrs: false,
    })
    const iconComponent = shallowRef(null)
    const props = defineProps({
        name: String,
        source: {
            type: String,
            default: () => 'phosphor',
            validator: value => ['phosphor', 'custom'].includes(value),
        },
        title: String,
    })
    watch(
        () => props.name,
        async () => {
            let response
            if (props.source == 'phosphor') {
                response = await import('@phosphor-icons/vue')
            }
            if (props.source == 'custom') {
                response = await import('@/icons')
            }
            iconComponent.value = response ? response[props.name] : ''
        },
        { immediate: true }
    )
</script>