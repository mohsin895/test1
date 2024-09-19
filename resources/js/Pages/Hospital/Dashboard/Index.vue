<template>
    <HospitalLayout aside="hospital">
        <div class="font-semibold text-xl mb-4">
            Options
        </div>
        <div class="flex gap-5 flex-wrap max-sm:grid grid-cols-2">
            <Link :href="route('hospitals.doctors.index')">
                <IconButton
                    class="w-[150px] max-sm:w-full"
                    classes="from-cyan-600 to-cyan-400"
                    icon="PhUser"
                    label="Doctors"
                />
            </Link>
        </div>
    </HospitalLayout>
</template>

<script setup>
import HospitalLayout from "@/Layouts/HospitalLayout.vue";
import { Link } from "@inertiajs/vue3";
import IconButton from "@/Components/dashboard/IconButton.vue";

</script>