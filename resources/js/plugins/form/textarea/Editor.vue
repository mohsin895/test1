<template>
    <div 
        :class="wrapperClass" 
        class="relative"
    >
        <label 
            v-if="label" 
            class="mb-1 block text-black/80" 
            :class="labelClass"
        >
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        <CKEditor.component
            :editor="ClassicEditor"
            v-model="modelValue" 
            :config="editorConfig"
            height="300px"
        ></CKEditor.component>
        <span v-if="error" class="text-[14px] text-red-500 absolute top-full right-0">
            {{ error }}
        </span>
    </div>
</template>

<script setup>
    import CKEditor from '@ckeditor/ckeditor5-vue'
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
    import '@/plugins/form/textarea/ckStyleReset.css'
    import { onMounted } from 'vue'

    defineOptions({
        inheritAttrs: false,
        name: "CKEditorWrapper"
    })

    const props = defineProps({
        label: String,
        wrapperClass: {
            type: String,
        },
        labelClass: {
            type: String,
        },
        inputClass: {
            type: String,
        },
        error: {
            type: String,
        },
        disabled: {
            type: Boolean,
            default: false
        },
        required: {
            type: Boolean,
            default: false
        },
        rows: {
            type: [Number, String],
            default: 3
        }
    })

    /*global defineModel */
    const modelValue = defineModel()
</script>

<style>
    .ck.ck-content{
        height: calc(v-bind('props.rows') * 29px)
    }
</style>