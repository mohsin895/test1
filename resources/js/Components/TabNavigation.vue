<template>
    <div class="flex w-fit shadow overflow-auto  bg-white/10 backdrop-blur border border-white/20">
        <RouterLink
            v-for="(item, index) in navData"
            :key="index"
            :to="item.to"
            exact
            class="flex items-center gap-2 px-4 py-2 text-white/50 hover:text-white font-medium duration-300 border-r border-white/10"
            exact-active-class="!bg-primary !text-white"
            :class="(index == navData.length - 1) ? 'border-r-0' : ''"
        >
            <component :is="item.icon" size="20" />
            {{ item.title }}
        </RouterLink>
    </div>
</template>

<script setup>
    import { PhAirplane, PhCreditCard, PhAirplaneInFlight, PhUsers, PhBriefcase } from '@phosphor-icons/vue'
    const navData = [
        {
            title: 'Flights',
            icon: PhAirplane,
            to: {
                name: 'agent.flights'
            }
        },
        {
            title: 'Visa',
            icon: PhCreditCard,
            to: {
                name: 'agent.visa'
            }
        },
        {
            title: 'Fly Ticket Vendor',
            icon: PhAirplaneInFlight,
            to: {
                name: 'agent.ticketVendor'
            }
        },
        {
            title: 'Group Fare',
            icon: PhUsers,
            to: {
                name: 'agent.groupFare'
            }
        },
        {
            title: 'Tours',
            icon: PhBriefcase,
            to: {
                name: 'agent.tours'
            }
        },
    ]
</script>