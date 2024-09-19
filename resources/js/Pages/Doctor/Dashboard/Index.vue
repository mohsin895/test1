<template>
    <DoctorLayout :title="title" aside="doctor" hideSidebar>
        <div class="font-semibold text-xl mb-4">
            Options
        </div>
        <div class="flex gap-5 flex-wrap max-sm:grid grid-cols-2">
            <Link :href="route('doctor.configurations')">
                <IconButton
                    class="w-[150px] max-sm:w-full"
                    classes="from-amber-600 to-amber-400"
                    icon="PhGear"
                    label="Configurations"
                />
            </Link>
            <Link :href="route('doctor.calender')">
                <IconButton
                    class="w-[150px] max-sm:w-full"
                    classes="from-blue-600 to-blue-400"
                    icon="PhCalendar"
                    label="My Calendar"
                />
            </Link>
            <Link :href="route('doctor.appointments.index')">
                <IconButton
                    class="w-[150px] max-sm:w-full"
                    classes="from-teal-600 to-teal-400"
                    icon="PhCalendarCheck"
                    label="Appointments"
                />
            </Link>
            <Link :href="route('doctor.appointments.index')">
                <IconButton
                    class="w-[150px] max-sm:w-full"
                    classes="from-teal-600 to-teal-400"
                    icon="PhCalendarCheck"
                    label="Book New"
                />
            </Link>
            <Link :href="route('doctor.schedule.index')">
                <IconButton
                    class="w-[150px] max-sm:w-full"
                    classes="from-cyan-600 to-cyan-400"
                    icon="PhClock"
                    label="Schedule"
                />
            </Link>
            <Link :href="route('doctor.advertising.index')">
                <IconButton
                    class="w-[150px] max-sm:w-full"
                    classes="from-lime-600 to-lime-400"
                    icon="PhAddressBook"
                    label="Advertisement"
                />
            </Link>
            <Link :href="route('doctor.compounder.index')">
                <IconButton
                    class="w-[150px] max-sm:w-full"
                    classes="from-emerald-600 to-emerald-400"
                    icon="PhUser"
                    label="Compounder"
                />
            </Link>
        </div>

        <div class="font-semibold mt-12 text-xl mb-4">
            Ongoing Ads
        </div>
        <div class="gap-5 mt-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="(item, key) in ongoingAds"
                :key="key"
                class="border border-slate-300 shadow-md rounded relative bg-white px-2 py-8"
            >
                <AdvertiseItem 
                    :item="item"
                />
            </div>
        </div>
        <div class="mt-12">
            <div class="font-semibold text-xl mb-4">
                Stats
            </div>
            <div
                class="mb-12 grid gap-x-6 gap-y-10 md:grid-cols-2 xl:grid-cols-4"
            >
                <Card label="Today's Appointment" icon="PhCalendarCheck" classes="from-teal-600 to-teal-400">
                    {{ todaysAppointments }}
                </Card>
                <Card label="Total Chamber" icon="PhGarage" classes="from-orange-600 to-orange-400">
                    {{ totalChamber }}
                </Card>
                <Card label="Total Compounder" icon="PhUser" classes="from-green-600 to-green-400">
                    {{ totalCompounder }}
                </Card>
                <Card label="Total Appointment" icon="PhCalendarCheck" classes="from-teal-600 to-teal-400">
                    {{ totalAppointment }}
                </Card>

                
            </div>

        </div>
    </DoctorLayout>
</template>

<script setup>
import DoctorLayout from "@/Layouts/DoctorLayout.vue";
import IconButton from "@/Components/dashboard/IconButton.vue";
import Card from "@/Components/dashboard/Card.vue";
import { Link } from "@inertiajs/vue3";
import AdvertiseItem from '@/Pages/Doctor/Advertising/fragments/AdvertiseItem.vue'

defineProps({
    todaysAppointments: Number,
    totalAppointment: Number,
    totalCompounder: Number,
    totalChamber: Number,
    ongoingAds: {
        type: Array,
        default: []
    }
});
</script>
