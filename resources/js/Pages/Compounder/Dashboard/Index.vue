<template>
    <CompounderLayout aside="compounder">
        <div class="font-semibold text-xl mb-4">
            Options
        </div>
        <div class="flex gap-5 flex-wrap max-sm:grid grid-cols-2">
            <Link :href="route('compounder.appointments.index')">
                <IconButton
                    class="w-[150px] max-sm:w-full"
                    classes="from-teal-600 to-teal-400"
                    icon="PhCalendarCheck"
                    label="Appointment"
                />
            </Link>
            <Link :href="route('compounder.schedule.index')">
                <IconButton
                    class="w-[150px] max-sm:w-full"
                    classes="from-cyan-600 to-cyan-400"
                    icon="PhClock"
                    label="Schedule"
                />
            </Link>
        </div>
    </CompounderLayout>
</template>

<script setup>
import CompounderLayout from "@/Layouts/CompounderLayout.vue";
import { Link } from "@inertiajs/vue3";
import IconButton from "@/Components/dashboard/IconButton.vue";

</script>
