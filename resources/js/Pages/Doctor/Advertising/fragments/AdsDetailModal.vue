<template>
    <form
        class="mx-auto rounded-md bg-white p-6 shadow-md w-[320px] md:w-[500px] lg:min-w-[700px]"
    >
        <h2 class="mb-4 text-2xl font-bold">Advertisement Details</h2>
        <div
            class="mb-4 grid grid-cols-2 gap-4 border border-slate-200 rounded-md p-4 text-center"
        >
            <div class="text-center col-span-3">
                <span
                    >Advertised By:
                    <span class="font-semibold">{{
                        selectedAdvertise.advertise.created_by.name
                    }}</span></span
                >
            </div>
            <div>
                <label
                    for="offer-price"
                    class="mb-2 block font-semibold text-gray-700"
                    >Offer Price</label
                >
                BDT
                {{
                    Number(
                        get(selectedAdvertise, "advertise.offer_price") || 0
                    ).toLocaleString()
                }}
            </div>
            <div>
                <label for="days" class="mb-2 block font-semibold text-gray-700"
                    >Days</label
                >
                {{ get(selectedAdvertise, "advertise.days") }}
            </div>
        </div>
        <div class="mb-4 border border-slate-200 rounded-md p-4 text-left">
            <label
                for="medicine-name"
                class="mb-2 block font-semibold text-gray-700"
                >Medicine Name</label
            >
            {{ special_medicines }}
            <div v-if="!selectedAdvertise.advertise.special_medicines">
                <span class="text-left">Not Available</span>
            </div>
        </div>
        <div class="mb-4 border border-slate-200 rounded-md p-4 text-left">
            <label
                for="medicine-image"
                class="mb-2 block font-semibold text-gray-700"
                >Medicine Images</label
            >
            <div class="flex flex-wrap space-x-2 mt-2">
                <div v-if="!selectedAdvertise.advertise.media.length">
                    <span class="text-left">Not Available</span>
                </div>
                <div v-else class="flex flex-wrap space-x-3">
                    <div
                        v-for="(media, index) in get(
                            selectedAdvertise,
                            'advertise.media'
                        ) || []"
                        :key="index"
                    >
                        <img :src="get(media, 'path')" class="border border-slate-100 shadow p-3 rounded w-[150px] h-[150px]" />
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-4 border border-slate-200 rounded-md p-4 text-left">
            <label
                for="additional-notes"
                class="mb-2 block font-semibold text-gray-700"
                >Main Post</label
            >
            {{ get(selectedAdvertise, "advertise.main_post") }}
            <div v-if="!selectedAdvertise.advertise.main_post">
                <span class="text-left">Not Available</span>
            </div>
        </div>
    </form>
</template>

<script setup>
import { get } from "lodash";
import { computed } from "vue";

const props = defineProps({
    selectedAdvertise: {
        type: Object,
        default: {},
    },
});

const special_medicines = computed(() => {
    let medicine_text = "";
    try {
        medicine_text = JSON.parse(
            props.selectedAdvertise.advertise.special_medicines
        ).join(", ");
    } catch (error) {}
    return medicine_text;
});
</script>
