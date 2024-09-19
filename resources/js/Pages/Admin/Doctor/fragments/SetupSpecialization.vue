<template>
    <ul class="grid w-full gap-6 grid-cols-1 md:grid-cols-3">
        <li
            v-for="item in $page.props.specializations"
            :key="item.id"
        >
            <input type="checkbox" v-model="form.specializations" :id="item.id" :value="item.id" class="hidden peer" />
            <label 
                :for="item.id"
                class="select-none inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary hover:text-gray-600 peer-checked:text-gray-600 hover:bg-slate-100"
            >
                <div class="block">
                    <img class="mb-2 w-7 h-7 text-sky-500" :src="get(item, 'media.path')" alt="" />
                    <div class="w-full text-lg font-semibold">{{ item.name }}</div>
                </div>
            </label>
        </li>
    </ul>
    <NextPrev @action="move => handleSave(move)" />
    
</template>

<script setup>
import { get, each } from 'lodash'
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import NextPrev from '../NextPrev.vue'
import { useConfig } from '../useConfig'

const form = useForm({
    specializations: []
})

onMounted(() => {
    let old = get(usePage().props, 'doctor.specializations')
    each(old, item => {
        form.specializations.push(item.specialization_id)
    })
})

const { handleNextPrev, activeTab } = useConfig()

const handleSave = (move) => {
    let page = usePage().props
    form.post(route('app.doctors.save_specializations', page.doctor.id), {
        onSuccess(e) {
            if (Object.keys(e.props.errors).length == 0) {
                handleNextPrev(move)
            }
        }
    })
}

</script>