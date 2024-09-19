<template>
    <Input.Native
        :id="uid"
        type="checkbox"
        :modelValue="modelValue"
        @update:modelValue="setModelValue"
        :value="value"
        class="hidden"
    />
    <label 
        :for="uid" 
        class="flex gap-1 items-center"
        :class="modelValue.includes(value) ? 'text-primary' : ''"
    >
        <Icon 
            :name="modelValue.includes(value) ? 'PhCheckSquare' : 'PhSquare'" size="20" 
        />
        {{ label }}
    </label>
</template>

<script setup>
    import { Input } from '@/plugins/form'
    import { inject } from 'vue'
    import { useForm } from '@/helper/useForm'
    import { Icon } from '@/plugins/ui'

    const { modelValue, setModelValue } = inject('modelValue')
    const { uid } = useForm()

    defineProps({
        label: {
            type: String,
            required: true,
        },
        value: {
            type: String,
            required: true
        }
    })
</script>