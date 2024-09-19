<template>
    <CompounderLayout title="Appointments">
        <form class="w-full mx-auto" @change="fetchAppointments">
            <div class="flex w-full gap-3">
                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Select Day
                    </label>
                    <select
                        id="countries"
                        v-model="type"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected disabled>Choose an option</option>
                        <option value="today">Today</option>
                        <option value="tomorrow">Tomorrow</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="previous">Previous</option>
                        
                    </select>
                </div>

                <div>
                    <label for="chambers" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Select a Chamber
                    </label>
                    <select
                        id="chambers"
                        v-model="chamber_id"
                        @change="getSlot"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected disabled>Choose an option</option>
                        <option v-for="(doctorChamber, index) in compounder.compounder_details" :key="index" :value="doctorChamber.chamber_id">
                            {{ doctorChamber.chamber.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label for="slots" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Select a Slot
                    </label>
                    <select
                        id="slots"
                        v-model="slot_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected disabled>Choose an option</option>
                        <option v-for="(slot, index) in slots" :key="index" :value="slot.id">
                            {{convert24to12( slot.from_time) }} - {{ convert24to12(slot.to_time) }}
                        </option>
                    </select>
                </div>
            </div>
        </form>

        <div v-if="appointments.length > 0" class="relative overflow-x-auto mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Patient name</th>
                        <th scope="col" class="px-6 py-3">Phone</th>
                        <th scope="col" class="px-6 py-3">Age</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="appointment in appointments" :key="appointment.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ appointment.patient_name }}
                        </th>
                        <td class="px-6 py-4">{{ appointment.phone }}</td>
                        <td class="px-6 py-4">{{ appointment.age }}</td>
                        <td class="px-6 py-4">{{ appointment.date }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="text-center mt-4 text-gray-500">No appointments found</div>
    </CompounderLayout>
</template>

<script setup>
import CompounderLayout from "@/Layouts/CompounderLayout.vue";
import { ref } from "vue";
import axios from "axios";
import Helper, { convert24to12 } from "@/helper";
const props = defineProps({
    compounder: {
        type: Object,
        default: {},
    },
   
});
const type = ref('');
const chamber_id = ref("");
const slot_id = ref("");
const slots = ref([]);
const appointments = ref([]);


const fetchAppointments = async () => {
    if (type.value || chamber_id.value || slot_id.value) {
        try {
            const response = await axios.post(route("compounder.bookNew.getAppointments"), {
                type: type.value,
                chamber_id: chamber_id.value,
                slot_id: slot_id.value,  // Include slot_id here
            });
            appointments.value = response.data.data;
        } catch (error) {
            console.error("Error fetching appointments:", error);
        }
    }
};




const getSlot = async () => {
    if (chamber_id.value) {
        try {
            const response = await axios.post(route("compounder.bookNew.slot"), {
                chamber_id: chamber_id.value,
            });
            slots.value = response.data.slots;
        } catch (error) {
            console.error("Error fetching slots:", error);
        }
    }
};
</script>
