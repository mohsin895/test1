<template>
    <PharmaceuticalLayout title="Dashboard">
        <div class="font-semibold text-xl mb-4">Options</div>
        <div class="flex gap-5 flex-wrap">
            <Link :href="route('pharmaceutical.advertisings.index')">
                <IconButton
                    class="w-[190px]"
                    classes="from-amber-600 to-amber-400"
                    icon="PhUsersFour"
                    label="Advertise Now"
                />
            </Link>
            <Link :href="route('pharmaceutical.advertisings.show')">
                <IconButton
                    class="w-[190px]"
                    classes="from-lime-600 to-lime-400"
                    icon="PhListChecks"
                    label="Advertisements"
                />
            </Link>
        </div>
        <!-- <div class="mt-12">
            <div class="font-semibold text-xl mb-4">Stats</div>
            <div
                class="mb-12 grid gap-x-6 gap-y-10 md:grid-cols-2 xl:grid-cols-4"
            >
                <Card
                    label="Total Advertisement"
                    icon="PhUsersFour"
                    classes="from-teal-600 to-teal-400"
                >
                </Card>
            </div>

        </div> -->
    </PharmaceuticalLayout>
</template>

<script setup>
import PharmaceuticalLayout from "@/Layouts/PharmaceuticalLayout.vue";
import IconButton from "@/Components/dashboard/IconButton.vue";
import Card from "@/Components/dashboard/Card.vue";
import { Link } from "@inertiajs/vue3";
</script>
