<template>
    <div :class="wrapperClass" class="relative">
        <label 
            class="mb-1 block text-black/80" 
            :class="labelClass"
            :for="uid"
        >
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        <div 
            class="border rounded flex items-center px-3 py-1"
            :class="[
                disabled ? 'opacity-60 bg-gray-100 pointer-events-none select-none' : '',
                error ? 'border-red-500' : 'border-black/10'
            ]"
        >
            <button
                v-if="iconPosition == 'left'"
                @click="$emit('iconClick')"
                class="pr-2 outline-none"
            >
                <Icon 
                    :size="iconSize"
                    :name="icon"
                    :class="iconClass"
                    :color="iconColor"
                />
            </button>
            <Select.Native
                :id="uid"
                v-model="_localModelValue"
                :options="options"
                :itemKey="itemKey"
                :itemValue="itemValue"
                :defaultOption="defaultOption"
                class="appearance-none py-[2px] w-full block"
                :class="inputClass"
                v-bind="$attrs"
            />
            <button 
                v-if="iconPosition == 'right'"
                @click="$emit('iconClick')"
                class="pl-2 outline-none"
            >
                <Icon 
                    :size="iconSize"
                    :name="icon"
                    :class="iconClass"
                    :color="iconColor"
                />
            </button>
        </div>
        <span v-if="error" class="text-[10px] text-red-500 absolute top-full right-0">
            {{ error }}
        </span>
    </div>
</template>

<script setup>
    import { Select } from '@/plugins/form'
    import { useForm } from '@/helper/useForm'
    import { Icon } from '@/plugins/ui'

    defineOptions({
        inheritAttrs: false,
        name: "PrimarySelect"
    })

    defineProps({
        options: Array,
        itemKey: {
            type: String,
            default: 'title'
        },
        itemValue: {
            type: String,
            default: 'id'
        },
        defaultOption: {
            type: String,
            default: '-- Select any --'
        },
        label: String,
        icon: {
            type: String,
            default: "PhCaretDown"
        },
        iconPosition: {
            type: String,
            default: 'right'
        },
        iconSize: {
            type: [Number, String],
            default: '12'
        },
        iconColor: {
            type: String,
            default: '#444'
        },
        iconClass: {
            type: String
        },
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
    const _localModelValue = defineModel()
</script>