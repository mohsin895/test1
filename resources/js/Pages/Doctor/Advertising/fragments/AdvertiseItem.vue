<template>
    <div class="flex flex-col text-center mb-4">
        <span class="font-semibold">{{
            get(item, "advertise.created_by.name")
        }}</span>
        <span>{{ get(item, "advertise.created_by.phone") }}</span>
        <span>
            Offer Price - BDT
            {{
                Number(
                    +get(item, "advertise.offer_price") || 0
                ).toLocaleString()
            }}
        </span>
        <span> Days - {{ get(item, "advertise.days") }} </span>
    </div>

    <div v-if="item.status === 'approved' && item.start_at && item.end_at">
        <div class="flex text-sm font-bold items-center justify-center mt-2">
            <span v-if="item.status === 'approved'" class="ml-1 mr-1">{{
                format(new Date(item.start_at), 'dd MMM, yyyy')
            }}</span>
            -
            <span v-if="item.status === 'approved'" class="ml-1">{{
                format(new Date(item.end_at), 'dd MMM, yyyy')
            }}</span>
        </div>
    </div>
    <div
        v-if="item.status != 'approved' && item.status != 'rejected'"
        class="flex flex-wrap space-x-2 mt-4 w-full items-center justify-center"
    >
        <Button.Primary @click="handleApprove(item.id)">
            <Icon name="PhCheck" size="20" weight="bold" />
        </Button.Primary>
        <Button.Danger @click="handleReject(item.id)">
            <Icon name="PhX" size="20" weight="bold" />
        </Button.Danger>
    </div>
    <button
        class="absolute top-0 right-0 mt-1 mr-1 rounded-full px-2 py-2 border shadow-md cursor-pointer"
        @click="selectedAdvertise=item"
        title="View Details"
    >
        <Icon name="PhEye" size="16" />
    </button>

    <Modal v-model="selectedAdvertise">
        <AdsDetailModal
            v-if="selectedAdvertise"
            :selectedAdvertise="selectedAdvertise"
        />
    </Modal>
</template>

<script setup>
import { get } from "lodash";
import useDoctor from "@/Pages/Doctor/Advertising/useDoctor";
import { Button, Icon, Modal } from "@/plugins/ui";
import { format } from 'date-fns';
import { ref } from 'vue'
import AdsDetailModal from "./AdsDetailModal.vue";

const props = defineProps({
    item: Object,
});

const selectedAdvertise = ref(false);

const {
    form,
    filteredAdvertise,
    adsDetailModal,
    getAdsByStatus,
    handleApprove,
    handleReject,
} = useDoctor();
</script>
