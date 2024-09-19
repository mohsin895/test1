<template>
    <ul class="grid w-full gap-6 grid-cols-1 md:grid-cols-4">
        <li
            v-for="item in $page.props.designations"
            :key="item.id"
        >
            <input type="radio" v-model="form.designation_id" :id="item.id" :value="item.id" class="hidden peer" />
            <label 
                :for="item.id"
                class="select-none inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary hover:text-gray-600 peer-checked:text-gray-600 hover:bg-slate-100"
            >
                <div class="block">
                    <div class="w-full text-lg font-semibold">{{ item.name }}</div>
                </div>
            </label>
        </li>
    </ul>
    <div class="py-5">
        <span class="font-semibold">Please Select Institution</span>
       
        <Select.Primary 
            v-model="form.medical_id"
            :options="$page.props.medicals"
            itemKey="id"
            itemValue="name"
        />
    </div>
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
    designation_id: null,
    medical_id: null,
})

onMounted(() => {
    let pageProps = get(usePage().props, 'doctor.designations')
    // console.log(pageProps);
    form.medical_id = get(pageProps[0], 'medical_id')
    form.designation_id = get(pageProps[0], 'designation_id')
})

const handleSave = (move) => {
    if (move > 0) {
        let pageProps = usePage().props
        form.post(route('doctor.save_designations'), {
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