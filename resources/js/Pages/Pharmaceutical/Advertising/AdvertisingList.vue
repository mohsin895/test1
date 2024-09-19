<template>
    <PharmaceuticalLayout title="Advertising Lists">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div
                v-for="(advertise, index) in advertises"
                :key="index"
                class="bg-white border p-4 rounded shadow-md relative"
            >
                <div class="flex flex-col font-medium space-y-2 text-center">
                    <span
                        >
                        Offer Price -
                        <span class="font-semibold">{{ advertise.offer_price.toLocaleString() }} TK</span>
                    </span>
                    <span>Days - <span class="font-semibold">{{ advertise.days }}</span></span>
                    <span>
                        Special Medicines -
                        <span class="font-semibold">{{ JSON.parse(advertise.special_medicines).toString() }}</span>
                    </span>
                </div>

                <div
                    class="flex flex-row items-center justify-center space-x-2 mt-2"
                >
                    <div v-for="(item, index) in advertise.media" :key="index">
                        <img :src="get(item, 'path')" class="w-[50px]" />
                    </div>
                </div>
                <button
                    class="absolute top-0 right-0 mt-1 mr-1 rounded-full px-2 py-2 border shadow-md cursor-pointer"
                    @click="selectedAd=advertise"
                    title="View Details"
                >
                    <PhEye size="18" />
                </button>
            </div>
        </div>

        <Modal v-model="selectedAd">
            <div class="bg-white py-8 px-5 w-[400px] md:min-w-[600px]">
                <h2 class="text-xl font-semibold text-black mb-5">
                    Doctor Lists
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div
                        class="border p-2 rounded relative shadow-md"
                        v-for="(doctor, index) in selectedAd.doctor_advertise"
                        :key="index"
                    >
                        <div class="flex justify-center my-2">
                            <div
                                class="w-14 h-14 rounded-full bg-slate-200 border border-slate-300"
                            >
                                <Img 
                                    :src="get(doctor, 'media.path')" 
                                    fallBack="/frontend/avatar.jpeg"
                                    class="w-full h-full object-cover rounded-full object-center"
                                />
                            </div>
                        </div>
                        <h3 class="text-center font-semibold mt-2">
                            {{ get(doctor, "doctor_detail.name") }}
                        </h3>

                        <div
                            class="flex items-center justify-center mt-2 space-x-1"
                        >
                            <span>Status:</span>
                            <div v-html="getStatus(doctor.status)"></div>
                        </div>
                        <div v-if="doctor.status === 'approved'">
                            <div class="flex items-center justify-center my-1">
                                {{
                                    new Date(
                                        doctor.start_at
                                    ).toLocaleDateString("en-Us", {
                                        year: "numeric",
                                        month: "short",
                                        day: "numeric",
                                    })
                                }} - 
                                {{
                                    new Date(
                                        doctor.end_at
                                    ).toLocaleDateString("en-Us", {
                                        year: "numeric",
                                        month: "short",
                                        day: "numeric",
                                    })
                                }}
                            </div>
                            <div class="flex items-center justify-center my-2">
                                <Link :href="route('pharmaceutical.advertisings.doctor-prescription-list', doctor.id)">
                                    <Button.Primary outline>
                                        Manage Prescription
                                    </Button.Primary>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Modal>
    </PharmaceuticalLayout>
</template>

<script setup>
import PharmaceuticalLayout from "@/Layouts/PharmaceuticalLayout.vue";
import { get } from "lodash";
import { Button, Modal, Img } from "@/plugins/ui";
import { PhEye } from "@phosphor-icons/vue";
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    advertises: {
        type: Array,
        default: [],
    },
});

const selectedAd = ref(false);


const getDoctorsByAdId = (id) => {
    const selectedAds = props.advertises.find((ad) => ad.id === id);
    return selectedAds ? selectedAds.doctor_advertise : [];
};

const getStatus = (status) => {
    let statusStatement = "-";
    switch (status) {
        case "pending":
            statusStatement = "<span class='text-red-600'>Pending</span>";
            break;
        case "approved":
            statusStatement = "<span class='text-green-600'>Approved</span>";
            break;
        case "rejected":
            statusStatement = "<span class='text-red-600'>Rejected</span>";
            break;
        default:
            statusStatement = "<span class='text-red-600'>Pending</span>";
            break;
    }

    return statusStatement;
};
</script>
