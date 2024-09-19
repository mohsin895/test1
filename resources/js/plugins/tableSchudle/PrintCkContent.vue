<template>
    <div class="ck-content">
        <slot>
            <div v-html="content" />
        </slot>
    </div>
</template>

<script setup>
    import '@/plugins/form/textarea/ckStyleReset.css'
    defineProps({
        content: String
    })
</script>