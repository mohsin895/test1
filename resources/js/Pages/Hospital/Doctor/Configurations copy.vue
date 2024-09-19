<template>
    <Auth title="Configuration">
        <div>
            <div class="flex justify-between items-center mb-5">
                <h2 class="font-bold text-xl mb-5">
                    Setup specialization
                </h2>
                <Button.Primary :to="route('hospitals.doctors.index')">Back</Button.Primary>
            </div>
            <div class="flex flex-wrap gap-5">
                <label
                    v-for="(item, index) in specializations"
                    :key="item.id"
                    class="flex gap-2 select-none mb-3"
                >
                    <input type="checkbox" v-model="form.specializations" :value="item.id" name="specialization[]" class="">
                    <p>{{ item.name }}</p>
                </label>
            </div>

            <h2 class="font-bold text-xl mb-5">
                Setup designation
            </h2>

            <div class="flex flex-col flex-wrap gap-5">
                <div class="flex flex-wrap gap-5">
                    <div
                        v-for="(item, index) in designations"
                        :key="item.id"
                        class="flex flex-col"
                    >
                        <label
                            class="flex gap-2 select-none mb-3"
                        >
                            <input type="radio" @change="form.medical_id=null" name="designations" v-model="form.designations_id" :value="item.id" class="">
                            <p>{{ item.name }}</p>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                Medical
                <Select.Primary
                    :options="medicals"
                    v-model="form.medical_id"
                    itemKey="id"
                    itemValue="name"
                    wrapperClass="max-w-[450px]"
                />
            </div>


            <h2 class="font-bold text-xl mb-5">
                Setup degree
            </h2>

            <div class="flex flex-wrap gap-5 mb-5">
                <div
                    v-for="(item, index) in degreesPayload"
                    :key="item.id"
                    class="flex flex-col border border-gray-400 rounded p-4"
                >
                    <label
                        class="flex gap-2 select-none mb-3"
                    >
                        <input type="checkbox" @input="handleCheck(item, degrees[index])" :checked="item.id" class="">
                        <p>{{ degrees[index].name }}</p>
                    </label>
                    <div v-if="item.id">
                        <textarea v-model="item.note" placeholder="Note" rows="1" class="border border-gray-400 p-1 focus:outline-none"></textarea>
                    </div>
                </div>
            </div>

            <h2 class="font-bold text-xl mb-5">
                Fees setup
            </h2>

            <div class="flex flex-wrap gap-5 mb-5">
                <div>
                    Old fees
                    <Input.Primary
                        type="number"
                        v-model="form.old_fees"
                    />
                </div>
                <div>
                    New fees
                    <Input.Primary
                        type="number"
                        v-model="form.new_fees"
                    />
                </div>
                <div>
                    Report fees
                    <Input.Primary
                        type="number"
                        v-model="form.report_fees"
                    />
                </div>

                <label class="flex items-center gap-2">
                    <Radio.Group v-model="form.is_combine">
                        <Radio.Input :value="0" label="Combine" />
                        <Radio.Input :value="1" label="Particular" />
                    </Radio.Group>
                </label>
            </div>

            <h2 class="font-bold text-xl mb-5">
                Setup Week Days
            </h2>
            <div class="flex flex-wrap gap-5 mb-5">
                <div v-for="(item, index) in week_days" class="flex">
                    <label class="flex gap-4">
                        <input 
                            type="checkbox" 
                            @input="handleWeekDay(item, week_days)" 
                            :checked="form.weekdays.indexOf(item) > -1"
                        />
                        {{ item }}
                    </label>
                </div>
            </div>

            <h2 class="font-bold text-xl mb-5 flex justify-between max-w-[600px]">
                Chambers and slot setup
                <Button.Primary @click="addSlot">+</Button.Primary>
            </h2>
            <!-- <Select.Primary
                :options="chambers"
                v-model="item.chamber_id"
                itemKey="id"
                itemValue="name"
                wrapperClass="max-w-[450px] w-[300px]"
            /> -->
            <div class="relative overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-200 font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Select Chamber
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Start time
                            </th>
                            <th scope="col" class="px-6 py-3">
                                End time
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Patient limit
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="(item, index) in appointmentSlot"
                            class="bg-white border-b"
                        >
                            <th scope="row" class="px-6 py-4 font-medium">
                                <Input.Primary
                                    v-model="item.title"
                                />
                            </th>
                            <td class="px-6 py-4">
                                <Select.Primary
                                    :options="chambers"
                                    v-model="item.chamber_id"
                                    itemKey="id"
                                    itemValue="name"
                                    wrapperClass="max-w-[450px] w-[300px]"
                                />
                                <!-- <Input.AutoComplete
                                    :itemKey="'id'"
                                    :itemValue="'name'"
                                /> -->
                            </td>
                            <td class="px-6 py-4">
                                <input type="time" v-model="item.from_time" />
                            </td>
                            <td class="px-6 py-4">
                                <input type="time" v-model="item.to_time" />
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium">
                                <Input.Primary
                                    type="number"
                                    v-model="item.limit"
                                />
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium">
                                <Button.Danger @click="appointmentSlot.splice(index, 1)">X</Button.Danger>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <Button.Primary @click="handleSave" :loading="form.processing">
                Save
            </Button.Primary>
        </div>
    </Auth>
</template>

<script setup>
import Auth from "@/Layouts/Auth.vue";
import { get } from 'lodash'
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/plugins/ui'
import { Select, Input, Radio } from '@/plugins/form'
import { onMounted, ref, computed } from "vue";

const props = defineProps({
    designations: Array,
    specializations: Array,
    degrees: Array,
    chambers: Array,
    week_days: Array,
    medicals: Array,
    doctor: {
        type: Object,
        default: {}
    },
});

const degreesPayload = ref([])

const preparePayload = (degrees) => {
    if(!degrees?.length) return
    let olds = [];
    try {
        olds = get(props.doctor, 'doctor_details.degree_info') || [];

    } catch (error) {}
    degreesPayload.value = degrees.map(item => {
        let obj = { id: null, note: null }
        let found = olds.find(i => i.id == item.id)
        if (found) {
            obj.id = found.id
            obj.note = found.note
        }
        return obj
    })
}

const handleCheck = (payloadItem, degreeItem) => {
    payloadItem.id = payloadItem.id ? null : degreeItem.id
}

const handleWeekDay = (payloadItem, list) => {
    let index = form.weekdays.indexOf(payloadItem)
    
    if (index > -1) {
        form.weekdays.splice(index, 1)
        return
    }
    form.weekdays.push(payloadItem)
}

const appointmentSlot = ref([]);

const addSlot = () => {
    appointmentSlot.value.push({
        title: '',
        limit: 0,
        from_time: null,
        to_time: null,
        chamber_id: null,
    })
}

const form = useForm({
    specializations: [],
    designations_id: null,
    medical_id: null,
    old_fees: 0,
    new_fees: 0,
    report_fees: 0,
    is_combine: 1,
    degree_info: [],
    weekdays: [],
    slots: [],
    appointmentSlot: [],
    degree_note: [],
})


onMounted(() => {
    let specialization_ids = []

    preparePayload(props.degrees)

    try {
        specialization_ids = get(props.doctor, 'doctor_details.specialization_ids') || []
    } catch (error) {}

    form.old_fees = get(props.doctor, 'doctor_details.old_fees') || 0
    form.new_fees = get(props.doctor, 'doctor_details.new_fees') || 0
    form.report_fees = get(props.doctor, 'doctor_details.report_fees') || 0
    form.is_combine = get(props.doctor, 'doctor_details.is_combine') || 0


    form.specializations = specialization_ids
    form.designations_id = get(props.doctor, 'doctor_details.designations_id')
    form.medical_id = get(props.doctor, 'doctor_details.medical_id')

    appointmentSlot.value = get(props.doctor, 'slots') || []
    form.weekdays = get(props.doctor, 'doctor_details.weekdays') || []

})


const handleSave = () => {
    form.degree_info = degreesPayload.value.filter(item => item.id)
    form.appointmentSlot = appointmentSlot.value
    form.post(route('hospitals.doctors.save_configurations', props.doctor.id))
}

</script>
