<template>
    <Modal v-model="chamberSlotModal">
        <form
            class="mx-auto rounded-md bg-white p-6 shadow-md w-[320px] md:w-[500px] lg:min-w-[700px]"
        >
            <h2 class="mb-6 text-xl font-bold">Compounder Details</h2>
            <div v-if="selectedChamber">
                <div
                    class="flex flex-col mb-4 border border-slate-200 rounded-md p-4 items-center justify-center"
                >
                    <span
                        ><span class="font-medium">Name:</span>
                        {{ selectedChamber.name }}</span
                    >
                    <span
                        ><span class="font-medium">Phone:</span>
                        {{ selectedChamber.phone }}</span
                    >
                    <span
                        ><span class="font-medium">Email:</span>
                        {{ selectedChamber.email }}</span
                    >
                </div>
                <div
                    class="mb-4 border border-slate-200 rounded-md p-4 text-center"
                >
                    <div
                        v-for="chamber in groupedSlotsByChamber"
                        :key="chamber.id"
                    >
                        <div class="mt-3 border border-slate-200 p-4">
                            <label
                                for="offer-price"
                                class="mb-2 block font-semibold text-gray-700"
                                >Chamber Name</label
                            >
                            {{ get(chamber, "chamber.name") }}
                        </div>
                        <div
                            class="mt-4 border border-slate-200 rounded-md p-4 text-center"
                            v-for="(slot, index) in chamber.slots"
                            :key="index"
                        >
                            <label
                                class="mb-2 block font-semibold text-gray-700"
                                >{{ `Slot - ${index + 1}` }}</label
                            >
                            {{ convert24to12(slot.from_time) }}
                            -
                            {{ convert24to12(slot.to_time) }}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </Modal>
</template>

<script setup>
import { Modal } from "@/plugins/ui";
import { get } from "lodash";
import Helper, { convert24to12 } from "@/helper";
import { useCompounder } from "./useCompounder.js";

const { chamberSlotModal } = useCompounder();

defineProps({
    selectedChamber: Object,
    groupedSlotsByChamber: Object,
});
</script>
