<template>
    <DoctorLayout title="Advertising Lists">
        <div class="flex flex-wrap justify-between">
            <label>Filter by:</label>
            <div class="flex flex-wrap gap-2">
                <div
                v-for="item in adsStatus"

                @click="() => {
                        form.status = item.key;
                        getAdsByStatus()
                    }"
                    class="first-letter:uppercase py-1 px-3 border select-none cursor-pointer"
                    :class="form.status == item.key ? 'border-primary text-white bg-primary' : 'border-primary text-primary'"
                >
                    {{ item.value }}
                </div>
            </div>
        </div>
        <div class="gap-5 mt-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="(item, key) in filteredAdvertise"
                :key="key"
                class="border border-slate-300 shadow-md pt-5 pb-4 rounded relative mb-2"
            >
                <AdvertiseItem 
                    :item="item"
                    @openModal="value => selectedAdvertise = value"
                />
            </div>
        </div>
        <div v-if="!filteredAdvertise.length && !loadingAdvertise" class="min-h-[200px] grid place-content-center text-xl font-semibold text-slate-500">
            No data found
        </div>

        
    </DoctorLayout>
</template>

<script setup>
import DoctorLayout from "@/Layouts/DoctorLayout.vue";

import { Button, Modal } from "@/plugins/ui";
import { Select } from "@/plugins/form";
import { PhEye } from "@phosphor-icons/vue";
import { ref, onMounted } from "vue";
import { get } from 'lodash'
import useDoctor from "@/Pages/Doctor/Advertising/useDoctor";
import AdvertiseItem from './fragments/AdvertiseItem.vue'

const {
    form,
    filteredAdvertise,
    loadingAdvertise,
    getAdsByStatus,
    handleApprove,
    handleReject,
} = useDoctor();



onMounted(() => {
    getAdsByStatus();
});

const adsStatus = [
    {
        key: "all",
        value: "All",
    },
    {
        key: "Ongoing",
        value: "ongoing",
    },
    {
        key: "Pending",
        value: "pending",
    },
    {
        key: "Completed",
        value: "completed",
    },
    {
        key: "Rejected",
        value: "rejected",
    },
];
</script>
