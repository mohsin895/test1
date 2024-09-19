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
        <div class="relative">
            <button
                v-if="iconPosition == 'left' && icon"
                @click="$emit('iconClick')"
                class="absolute left-2 top-1/2 transform -translate-y-1/2"
            >
                <Icon
                    :size="iconSize"
                    :name="icon"
                    class="pr-2"
                    :class="iconClass"
                    :color="iconColor"
                />
            </button>
            <Input.Native
                :id="uid"
                v-bind="$attrs"
                v-model="modelValue"
                class="focus:border-black/60 w-full rounded py-[7px] text-sm text-gray-900 border"
                :class="[
                    icon
                        ? iconPosition == 'left'
                            ? 'pl-7 pr-3'
                            : 'pr-7 pl-3'
                        : 'px-3',
                    inputClass,
                    disabled
                        ? 'opacity-60 bg-gray-300 pointer-events-none select-none'
                        : 'bg-transparent',
                    error ? 'border-red-500' : 'border-black/40',
                ]"
            />
            <button
                v-if="iconPosition == 'right' && icon"
                @click="$emit('iconClick')"
                class="absolute right-2 top-1/2 transform -translate-y-1/2"
            >
                <Icon
                    :size="iconSize"
                    :name="icon"
                    class="pl-2"
                    :class="iconClass"
                    :color="iconColor"
                />
            </button>
        </div>
        <span
            v-if="error"
            :class="[
                'text-[10px] text-red-500 absolute top-full',
                errorPosition == 'left' ? 'left-0' : 'right-0',
                errorClass
            ]"
        >
            {{ error }}
        </span>
    </div>
</template>

<script setup>
import { Icon } from "@/plugins/ui";
import { useForm } from "@/helper/useForm";
import { Input } from "@/plugins/form";

defineOptions({
    inheritAttrs: false,
    name: "PrimaryInput",
});
defineProps({
    label: String,
    icon: Object,
    iconPosition: {
        type: String,
        default: "left",
    },
    errorPosition: {
        type: String,
        default: "right",
    },
    errorClass: {
        type: String,
        default: "",
    },
    iconSize: {
        type: [Number, String],
        default: "25",
    },
    iconColor: {
        type: String,
        default: "inherit",
    },
    iconClass: {
        type: String,
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
        default: false,
    },
    required: {
        type: Boolean,
        default: false,
    },
});

const { uid } = useForm();

/*global defineModel */
const modelValue = defineModel();
</script>
