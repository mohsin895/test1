<template>
    
    <Input.Primary
        v-model="form.old_fees"
        label="Old Fees"
    />
    <Input.Primary
        v-model="form.new_fees"
        label="New Fees"
    />
    <Input.Primary
        v-model="form.report_fees"
        label="Report Fees"
    />

    <NextPrev @action="move => handleSave(move)" />
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { useConfig } from '../useConfig'
import { Select, Input } from '@/plugins/form'
import NextPrev from '../NextPrev.vue';
import { onMounted } from 'vue'
import { get } from 'lodash'

const { handleNextPrev } = useConfig()

const form = useForm({
    old_fees: null,
    new_fees: null,
    report_fees: null,
})

onMounted(() => {
    let pageProps = usePage().props
    form.old_fees = get(pageProps, 'doctor.doctor_details.old_fees')
    form.new_fees = get(pageProps, 'doctor.doctor_details.new_fees')
    form.report_fees = get(pageProps, 'doctor.doctor_details.report_fees')
})

const handleSave = (move) => {
    if (move > 0) {
        let pageProps = usePage().props
        form.post(route('app.doctors.save_fees', pageProps.doctor.id), {
            onSuccess(e) {
                if (Object.keys(e.props.errors).length==0) {
                    handleNextPrev(move)
                }
            }
        })
    } else {
        handleNextPrev(move)
    }
}

</script>