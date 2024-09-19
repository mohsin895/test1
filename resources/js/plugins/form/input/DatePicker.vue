<template>
    <div :class="wrapperClass" class="relative">
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
            <!-- <Input.Native
                :id="uid"
                v-bind="$attrs"
                v-model="modelValue"
                class="focus:border-black/30 bg-transparent w-full rounded py-[7px] text-sm text-gray-900 border border-black/10"
                :class="[
                    icon ? iconPosition == 'left' ? 'pl-7 pr-3' : 'pr-7 pl-3' : 'px-3', 
                    inputClass,
                    disabled ? 'opacity-60 bg-gray-100 pointer-events-none select-none' : ''
                ]"
            /> -->
            <div
                :class="[
                    inputClass
                ]"
            >
                <DatePicker
                    v-model="modelValue"
                    :modelType="format"
                    :close-on-auto-apply="false"
                    :clearable="clearable"
                    v-bind="$attrs"
                    auto-apply
                    :enable-time-picker="timePicker"
                    :format="`${format}`"
                    :month-change-on-scroll="false"
                    model-type="yyyy-MM-dd"
                    :teleport="true"
                    @update:model-value="closePicker"
                    ref="datepicker"
                />
            </div>
        </div>
        <span v-if="error" class="text-[10px] text-red-500 absolute top-full right-0">
            {{ error }}
        </span>
    </div>
</template>

<!-- https://vue3datepicker.com/props/modes-configuration/ -->
<script setup>
    import { useForm } from '@/helper/useForm'
    import DatePicker from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'
    import { ref } from 'vue'

    defineOptions({
        inheritAttrs: false,
        name: "PrimaryInput"
    })
    defineProps({
        label: String,
        icon: Object,
        format: {
            type: String,
            default: 'dd-MM-yyyy'
        },
        clearable: {
            type: Boolean,
            default: true
        },
        timePicker: {
            type: Boolean,
            default: false
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
        required: {
            type: Boolean,
            default: false
        }
    })

    const { uid } = useForm()
    const datepicker = ref(null)
    const closePicker = () => {
        datepicker.value.closeMenu()
    }

    /*global defineModel */
    const modelValue = defineModel()
</script>