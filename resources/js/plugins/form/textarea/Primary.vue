<template>
    <div 
        :class="wrapperClass" 
        class="relative"
    >
        <label 
            v-if="label" 
            class="mb-1 block text-black/80" 
            :class="labelClass"
            :for="uid"
        >
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        <div 
            class="relative"
        >
            <Textarea.Native
                :id="uid"
                v-bind="$attrs"
                v-model="modelValue"
                class="focus:border-black/30 bg-transparent w-full rounded py-[7px] text-sm text-gray-900 border"
                :class="[
                    icon ? iconPosition == 'left' ? 'pl-7 pr-3' : 'pr-7 pl-3' : 'px-3', 
                    inputClass,
                    disabled ? 'opacity-60 bg-gray-100 pointer-events-none select-none' : '',
                    error ? 'border-red-500' : 'border-black/10'
                ]"
            />
        </div>
        <span v-if="error" class="text-[10px] text-red-500 absolute top-full right-0">
            {{ error }}
        </span>
    </div>
</template>

<script setup>
    import { useForm } from '@/helper/useForm'
    import { Textarea } from '@/plugins/form'

    defineOptions({
        inheritAttrs: false,
        name: "TextareaPrimary"
    })
    defineProps({
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
        }
    })

    const { uid } = useForm()

    /*global defineModel */
    const modelValue = defineModel()
</script>