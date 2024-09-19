<template>
<div
                class="mb-4 border border-slate-200 rounded shadow p-4 text-center"
            >
                <div class="grid grid-cols-3">
                    <div></div>
                    <div>
                        <span
                            >Advertised By:
                            <span class="font-semibold">{{
                                get(doctorAdvertise, "advertise.created_by.name")
                            }}</span>
                        </span>
                    </div>
                    <div class="flex justify-end">
                        <span class="font-semibold"
                            >Total Prescriptions:
                            <span class="text-green-500">{{ doctorAdvertise.totalAppointments }}</span></span
                        >
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-center">
                        <span class="inline-flex items-center py-0.5 px-2">
                            <span
                                class="h-2 w-2 rounded-full mr-1"
                                :class="{
                                    'bg-green-500':
                                        doctorAdvertise.status === 'approved' &&
                                        doctorAdvertise.end_at > doctorAdvertise.start_at,

                                    'bg-red-500':
                                        doctorAdvertise.status === 'approved' &&
                                        doctorAdvertise.end_at < doctorAdvertise.start_at,
                                }"
                            ></span>
                            <div
                                v-if="
                                    doctorAdvertise.status === 'approved' &&
                                    doctorAdvertise.end_at > doctorAdvertise.start_at
                                "
                            >
                                <span class="text-green-500"> Ongoing </span>
                            </div>
                            <div
                                v-else-if="
                                    doctorAdvertise.status === 'approved' &&
                                    doctorAdvertise.end_at <= doctorAdvertise.start_at
                                "
                            >
                                <span class="text-red-500"> Completed </span>
                            </div>
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-3 lg:grid-cols-3 mt-3">
                    <div class="text-left">
                        <span class="mb-2 block font-semibold text-gray-700"
                            >Offer Price</span
                        >
                        <div class="text-left">
                            BDT
                            {{
                                get(
                                    doctorAdvertise,
                                    "advertise.offer_price"
                                ).toLocaleString()
                            }}
                        </div>
                    </div>
                    <div>
                        <span class="mb-2 block font-semibold text-gray-700"
                            >Doctor</span
                        >
                        <a
                            class="hover:text-blue-700 font-semibold"
                            target="_blank"
                            :href="
                                route(
                                    'doctor-profile.doctorProfile',
                                    doctorAdvertise.doctor_detail.id
                                )
                            "
                            >{{ doctorAdvertise.doctor_detail.name }}</a
                        >
                    </div>
                    <div class="flex justify-end">
                        <div class="text-left">
                            <span class="mb-2 block font-semibold text-gray-700"
                                >Duration</span
                            >

                            <div>
                                {{
                                    new Date(
                                        doctorAdvertise.start_at
                                    ).toLocaleDateString("en-UK", {
                                        year: "numeric",
                                        month: "short",
                                        day: "numeric",
                                    })
                                }}
                                <span class="mx-1">To</span>
                                {{
                                    new Date(
                                        doctorAdvertise.end_at
                                    ).toLocaleDateString("en-UK", {
                                        year: "numeric",
                                        month: "short",
                                        day: "numeric",
                                    })
                                }}
                                ({{
                                    get(doctorAdvertise, 'advertise.days')
                                }}
                                Days)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-2 p-4 text-center">
                <div class="grid grid-cols-3">
                    <div></div>
                    <div>
                        <span
                            class="bg-green-100 border border-green-10 px-3 py-1.5 rounded-md text-green-800"
                            >{{
                                new Date(
                                    doctorAdvertise.start_at
                                ).toLocaleDateString("en-UK", {
                                    year: "numeric",
                                    month: "short",
                                    day: "numeric",
                                    weekday: "long",
                                })
                            }}</span
                        >
                    </div>
                    <!-- <div class="flex justify-end">
                        <span class="font-semibold"
                            >Prescriptions:
                            <span class="text-green-500">{{ doctorAdvertise.totalAppointments }}</span></span
                        >
                    </div> -->
                </div>
            </div>
</template>

<script setup>
import { get } from "lodash";
defineProps({
    doctorAdvertise: {
        type: Object,
    }
})
</script>