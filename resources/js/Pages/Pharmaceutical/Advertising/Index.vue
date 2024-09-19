<template>
    <PharmaceuticalLayout title="Advertising">
        <div class="mt-4">
            <h2 class="text-gray-800 mb-4 flex justify-between items-center">
                <div class="text-xl font-semibold">Designations</div>
            </h2>
            <div class="flex flex-wrap items-center">
                <label
                    class="mr-4"
                    v-for="(designation, index) in designations"
                    :key="index"
                >
                    <input
                        type="checkbox"
                        v-model="form.designation_ids"
                        name="designation_ids[]"
                        :value="designation.id"
                        class="mr-2"
                        @change="getDoctors()"
                    />
                    {{ designation.name }}
                </label>
            </div>
        </div>
        <div class="mt-4">
            <h2 class="text-gray-800 mb-2 flex justify-between items-center">
                <div class="text-xl font-semibold">Specializations</div>
            </h2>
            <div class="flex flex-wrap items-center">
                <label
                    class="mr-4"
                    v-for="(specialization, index) in specializations"
                    :key="index"
                >
                    <input
                        type="checkbox"
                        v-model="form.specialization_ids"
                        name="specialization_ids[]"
                        :value="specialization.id"
                        class="mr-2"
                        @change="getDoctors()"
                    />
                    {{ specialization.name }}
                </label>
            </div>
        </div>
        <div class="mt-4">
            <h2 class="text-gray-800 mb-2 flex justify-between items-center">
                <div class="text-xl font-semibold">Degrees</div>
            </h2>
            <div class="flex flex-wrap items-center">
                <label
                    class="mr-4"
                    v-for="(degree, index) in degrees"
                    :key="index"
                >
                    <input
                        type="checkbox"
                        v-model="form.degree_ids"
                        name="degree_ids[]"
                        :value="degree.id"
                        class="mr-2"
                        @change="getDoctors()"
                    />
                    {{ degree.name }}
                </label>
            </div>
        </div>
        <div class="mt-8">
            <h2 class="text-gray-800 mb-4 flex justify-between items-center">
                <div class="text-xl font-semibold">Doctors</div>
                <div>
                    <Button.Primary
                        v-if="advertise_form.doctor_ids.length > 1"
                        @click="advertise_form.doctor_ids = []"
                    >
                        Deselect All
                    </Button.Primary>
                    <div class="flex justify-end mt-2">
                        <Input.Primary
                            type="search"
                            placeholder="search here..."
                            v-model="search"
                            @input="getDoctors()"
                        />
                    </div>
                </div>
            </h2>
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
            >
                <div v-if="!filteredDoctors.data?.length">
                    <span class="text-red-500">No match found..</span>
                </div>
                <label
                    v-for="(doctor, index) in filteredDoctors.data"
                    :key="index"
                    class="relative"
                >
                    <input
                        type="checkbox"
                        v-model="advertise_form.doctor_ids"
                        name="doctor_ids[]"
                        :value="doctor.id"
                        class="hidden peer"
                    />
                    <div
                        class="border p-2 rounded relative shadow-md cursor-pointer hover:bg-slate-50 peer-checked:border-primary hover:border-primary"
                    >
                        <span
                            v-if="advertise_form.doctor_ids.includes(doctor.id)"
                            class="block w-5 h-5 rounded-full bg-primary absolute top-2 left-2"
                        ></span>
                        <div class="flex justify-center my-2">
                            <div
                                class="w-14 h-14 rounded-full bg-slate-200 border border-slate-300"
                            >
                                <img :src="get(doctor, 'media.path')" />
                            </div>
                        </div>
                        <h3 class="text-center font-semibold mt-2">
                            {{ doctor.name }}
                        </h3>
                        <div class="flex flex-wrap gap-1 justify-center">
                            <div
                                v-for="(
                                    doctorSpecialization, index
                                ) in doctor.specializations"
                                :key="index"
                            >
                                <span>{{
                                    get(
                                        doctorSpecialization,
                                        "specialization.name"
                                    )
                                }}</span>
                                <span
                                    v-if="
                                        index <
                                        doctor.specializations.length - 1
                                    "
                                    >,
                                </span>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <!-- Pagination -->
            <div
                class="flex items-center justify-center my-9"
                v-if="filteredDoctors.data?.length"
            >
                <div
                    v-for="(link, index) in filteredDoctors.links"
                    :key="index"
                >
                    <button
                        v-if="link.url"
                        :class="[
                            'px-3 py-2',
                            link.active
                                ? 'text-white bg-indigo-600'
                                : 'text-gray-500 hover:text-blue-500',
                        ]"
                        @click="getDoctors(link.url)"
                    >
                        <span v-html="link.label"></span>
                    </button>
                    <span
                        v-else
                        class="px-3 py-2 text-gray-500 hover:text-blue-500"
                        v-html="link.label"
                    ></span>
                </div>
            </div>

            <!-- Form start -->
            <form
                class="mx-auto bg-white my-9"
                @submit.prevent="handleSendRequest"
            >
                <div class="mb-4">
                    <label class="mr-2 block font-medium text-gray-600">
                        Days
                    </label>
                    <Select.Primary
                        v-model="advertise_form.days"
                        :options="days"
                        item-key="value"
                        item-value="value"
                        :error="advertise_form.errors.days"
                    />
                </div>
                <div class="block font-medium text-gray-600 mb-2">
                    Special Medicines
                </div>
                <div class="mb-4 flex items-start">
                    <div
                        class="grid gap-5 grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
                    >
                        <div
                            v-for="(
                                medicine, index
                            ) in advertise_form.special_medicines"
                            :key="index"
                        >
                            <div class="flex items-center space-x-2">
                                <Input.Primary
                                    type="text"
                                    v-model="
                                        advertise_form.special_medicines[index]
                                    "
                                />
                                <Button.Danger
                                    v-if="
                                        advertise_form.special_medicines
                                            .length > 1
                                    "
                                    @click="removeSpecialMedicine(index)"
                                    type="button"
                                >
                                    -
                                </Button.Danger>
                            </div>
                        </div>
                    </div>
                    <div class="ml-1">
                        <Button.Primary
                            type="button"
                            @click="addSpecialMedicine"
                        >
                            +
                        </Button.Primary>
                    </div>
                </div>
                <div class="block font-medium text-gray-600 mb-2">Images</div>
                <div class="mb-4 flex items-start">
                    <div
                        class="grid gap-5 grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
                    >
                        <div
                            v-for="(item, index) in mediaInput"
                            :key="index"
                            class="relative"
                        >
                            <Input.File
                                type="file"
                                @input="(e) => (item.media = e.target.files[0])"
                                class="w-full border py-1.5 px-1.5 rounded"
                            />
                            <Button.Danger
                                v-if="mediaInput.length > 1"
                                @click="removeMedia(index)"
                                type="button"
                                class="absolute top-0 right-0 mt-1 mr-1"
                            >
                                -
                            </Button.Danger>
                        </div>
                    </div>
                    <div class="ml-2">
                        <Button.Primary type="button" @click="addMedia">
                            +
                        </Button.Primary>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="mb-2 block font-medium text-gray-600"
                        >Offer Price</label
                    >
                    <input
                        v-model="advertise_form.offer_price"
                        :error="advertise_form.errors.offer_price"
                        class="w-full rounded-md border p-2 focus:outline-none focus:border-slate-400"
                    />
                </div>
                <div class="mb-6">
                    <label class="mb-2 block font-medium text-gray-600"
                        >Main Post</label
                    >
                    <textarea
                        v-model="advertise_form.main_post"
                        :error="advertise_form.errors.main_post"
                        rows="4"
                        class="w-full rounded-md border p-2 focus:outline-none focus:border-slate-400"
                    ></textarea>
                </div>
                <div class="flex justify-end">
                    <Button.Primary type="submit">
                        Send Request
                    </Button.Primary>
                </div>
            </form>
        </div>
    </PharmaceuticalLayout>
</template>

<script setup>
import PharmaceuticalLayout from "@/Layouts/PharmaceuticalLayout.vue";
import { Input, Select } from "@/plugins/form";
import { Button } from "@/plugins/ui";
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { isEmpty, get } from "lodash";

import { useAdvertising } from "@/Pages/Pharmaceutical/Advertising/useAdvertising";

const { form, search, getDoctors, filteredDoctors, loading } = useAdvertising();

defineProps({
    doctors: {
        type: Array,
        default: [],
    },
    designations: {
        type: Array,
        default: [],
    },
    specializations: {
        type: Array,
        default: [],
    },
    degrees: {
        type: Array,
        default: [],
    },
});

const mediaInput = ref([{ media: null }]);

const advertise_form = useForm({
    doctor_ids: [],
    days: null,
    special_medicines: [""],
    media: [],
    offer_price: null,
    main_post: "",
    status: "pending",
});

onMounted(() => {
    getDoctors();
});

function addSpecialMedicine() {
    advertise_form.special_medicines.push("");
}
function addMedia() {
    mediaInput.value.push({ media: null });
}
function removeMedia(index) {
    mediaInput.value.splice(index, 1);
}

function removeSpecialMedicine(index) {
    advertise_form.special_medicines.splice(index, 1);
}

const handleSendRequest = () => {
    advertise_form.media = mediaInput.value.map((i) => i.media);
    advertise_form.post(route("pharmaceutical.advertisings.sendRequest"), {
        onSuccess(e) {
            if (isEmpty(e.props.errors)) {
                advertise_form.reset();
                mediaInput.value = [{ media: null }];
            }
        },
    });
};

const days = [
    {
        key: 10,
        value: 10,
    },
    {
        key: 11,
        value: 11,
    },
    {
        key: 12,
        value: 12,
    },
    {
        key: 13,
        value: 13,
    },
];
</script>
