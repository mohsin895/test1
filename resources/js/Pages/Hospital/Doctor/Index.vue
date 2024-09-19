<template>
   <HospitalLayout :title="title">
    <div class="mb-4">
        <!-- <h2 class="text-gray-800 mb-2 flex justify-between items-center">
            <div class="text-xl font-semibold">Specializations</div>
            <Button.Info
                @click="
                    () => {
                        formModal = true;
                    }
                "
            >
                <Icon name="PhPlus" size="20" weight="bold" />
            </Button.Info>
        </h2> -->
        <!-- {{ selectedSpecialization }} -->
        <div class="flex flex-wrap items-center">
            <label 
                v-for="(item, index) in specializations"
                :key="index"
                class="mr-4"
            >
                <input
                    type="checkbox"
                    name="specialization_ids[]"
                    class="mr-2"
                    v-model="filter.specializations"
                    :value="item.id"
                />
                {{ item.name }}
            </label>
        </div>
    </div>
    <h2 class="text-gray-800 mb-4 flex justify-between items-center">
        <div class="text-xl font-semibold">Doctors</div>
        <Input.Primary
            placeholder="Search doctor..."
            type="search"
            v-model="filter.search"
        />
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-5">
        <div
            v-for="item in doctors"
            :key="item.id"
            class="border border-slate-200 shadow-md pt-5 pb-4 rounded relative"
        >
            <div class="grid place-content-center pt-4 pb-2">
                <div
                    class="w-16 h-16 border border-slate-300 shadow rounded-full overflow-hidden"
                >
                    <img
                        :src="DoctorLogo"
                        class="block w-full h-full object-cover object-center"
                        alt=""
                    />
                </div>
            </div>
            <div class="text-center">
                {{ item.name }}
            </div>
            <div class="text-center">
                {{ item.phone }}
            </div>
            <div v-if="item.specializations" class="text-center text-sm text-gray-600">
                {{ getSpecializationString(item.specializations || []) }}
            </div>

            <div class="flex justify-center mt-3 gap-3">
                <Button.Success 
                    @click="handleAppointmentVisit(item.id)" 
                    class="!px-2"
                >
                    <Icon
                        name="PhClipboardText"
                        size="20"
                        weight="bold"
                    />
                </Button.Success>
                <Button.Primary @click="handleAdvertisementVisit(item.id)" class="!px-2">
                    <Icon
                        name="PhEye"
                        size="20"
                        class="rotate-180"
                        weight="bold"
                    />

                  
                </Button.Primary>
                <Button.Warning @click="handleEdit(item)" class="!px-2">
                    <Icon
                        name="PhPencilSimpleLine"
                        size="20"
                        weight="bold"
                    />
                </Button.Warning>
                <!-- <Button.Primary @click="handleEdit(item)">
                    <Icon
                        name="PhPencilSimpleLine"
                        size="20"
                        weight="bold"
                    />
                </Button.Primary>
                <Button.Danger @click="handleDelete(item.id)">
                    <Icon name="PhX" size="20" weight="bold" />
                </Button.Danger> -->
            </div>

        </div>
    </div>

        <Modal v-model="formModal" @close="selectedItem = null">
            <Create v-if="formModal" :selectedItem="selectedItem" />
        </Modal>
    </HospitalLayout>
</template>

<script setup>
import HospitalLayout from "@/Layouts/HospitalLayout.vue";
import { Table, Button, Modal, Icon } from "@/plugins/ui";
import { Input } from '@/plugins/form'
import { ref, onMounted, watch } from "vue";
import { router } from "@inertiajs/vue3";
import Helper from "@/helper";
import DoctorLogo from "@/assets/doctor.png";
import Create from "./fragments/Create.vue";
import { get } from 'lodash'
import { useFilter } from './useFilter'
import { watchEffect } from "vue";

const props = defineProps({
    // data: {
    //     type: Array,
    //     default: [],
    // },
    specializations: {
        type: Array,
        default: []
    },
    roles: {
        type: Array,
        default: [],
    },
});

const formModal = ref(false);
const selectedItem = ref(null);
const { getDoctors, doctors } = useFilter()

const filter = ref({
    specializations: [],
    search: ''
})

let timeout;

watchEffect(() => {
    getDoctors(filter.value)
})
const handleEdit = (item) => {
    selectedItem.value = item;
    formModal.value = true;
};

const handleAppointmentVisit = (id) => {
    router.visit(route('hospitals.appointments.index', id))
}
const handleAdvertisementVisit = (id) => {
    router.visit(route('hospitals.appointments.index', id))
}

const getSpecializationString = (specializations) => {
    if(specializations) {
        return specializations.map(item => {
            return get(item, 'specialization.name')
        }).join(', ')
    }
}

// onMounted(() => {
//     getDoctors(filter.value)
// })

</script>
