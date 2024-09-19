<template>
    <form
        @submit.prevent="$emit('onSave')"
        class="bg-white py-8 px-5 w-[300px] md:min-w-[500px]"
    >
        <h2 class="font-semibold text-black mb-3">Edit Appointment</h2>
        <div class="grid md:grid-cols-2 gap-2">
            <Input.Primary
                type="text"
                label="Patient Name"
                required
                v-model="form.patient_name"
                :error="form.errors.patient_name"
            />
            <Input.Primary
                type="text"
                label="Phone"
                required
                v-model="form.phone"
                :error="form.errors.phone"
            />
            <Input.Primary
                type="text"
                label="Age"
                v-model="form.age"
                :error="form.errors.age"
            />
            <Input.DatePicker
                type="text"
                label="Date"
                required
                v-model="form.date"
                :error="form.errors.date"
            />
            <Select.Primary
                type="text"
                label="Patient Type"
                :options="patientTypes"
                itemKey="id"
                itemValue="name"
                required
                v-model="form.patient_type_id"
                :error="form.errors.patient_type_id"
            />
        </div>
        <div class="mt-5 flex justify-end">
            <Button.Primary
                type="submit"
                :disabled="form.processing"
                :class="{
                    'disabled:opacity-60 disabled:cursor-not-allowed': form.processing
                }"
                class="flex items-center gap-2"
                :loading="form.processing"
            >
                Save
            </Button.Primary>
        </div>
    </form>
</template>

<script setup lang="ts">
import { Table, Button, Modal, Icon } from "@/plugins/ui";
import { Input, Textarea, Select } from "@/plugins/form";

defineProps({
    patientTypes: {
        type: Array,
        default: []
    },
    form: Object
})
</script>